<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Job_Service_Cat;
use App\Models\SriLankaCities;
use App\Models\SriLankaDistricts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JobListingController extends Controller
{
    public function index()
    {
 
    $jobDetails = DB::table('job')
        ->select(
            'job.id as jobId',
            'job.user_id',
            'job.job_no',
            'users.first_name as ClientFirstName',
            'users.last_name as ClientLastName',
            'job.description as jobDescription',
            'job.city_id',
            'cities.name_en as cityName',
            'job.start_location',
            'job.end_location',
            'job.worker_id',
            'workers.first_name as workerFirstName',  // Worker first name
            'workers.last_name as workerLastName', 
            'job.status',
            'service_cat.name as serviceName',
            'service_cat.description as serviceDescription',
            'job.required_date',
            'job.required_time',
            'job.created_at',
            'job.preferred_sex'
        )
        ->leftJoin('job_service_cat', function ($join) {
            $join->on('job.id', '=', 'job_service_cat.job_id')
                ->whereRaw('job_service_cat.id = (SELECT MIN(id) FROM job_service_cat WHERE job_id = job.id)');
        })
        ->leftJoin('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
        ->leftJoin('users', 'job.user_id', '=', 'users.id')
        ->leftJoin('users as workers', 'job.worker_id', '=', 'workers.id')
        ->leftJoin('cities', 'job.city_id', '=', 'cities.id')
        ->orderByRaw('CASE WHEN job.status = 6 THEN 0 ELSE 1 END, job.created_at DESC')
       // ->paginate(10); 
        ->get();

    return view('admin.joblisting.index', compact('jobDetails'));
    }
    public function assign($jobId)
    {
        $districts = SriLankaDistricts::all();
        $workers = User::where('user_type', 3)
        ->where('status', 1) // Add this line to filter by status
        ->get();
        $job = DB::table('job')
        ->select(
            'job.id as jobId',
            'job.user_id',
            'users.first_name as userFirstName',
            'users.last_name as userLasttName',
            'job.description as jobDescription',
            'job.city_id',
            'cities.name_en as cityName',
            'job.start_location',
            'job.end_location',
            'job.worker_id',
            'workers.first_name as workerFirstName', // Worker First Name
            'workers.last_name as workerLastName',
            'job.status',
            'service_cat.name as serviceName',
            'service_cat.description as serviceDescription',
            'job.required_date',
            'job.required_time',
            'job.preferred_sex',
            'job.finishJobDescription',
            'job.created_at',
            'job.worker_area_id'
        )
        ->leftJoin('job_service_cat', function ($join) {
            $join->on('job.id', '=', 'job_service_cat.job_id')
                ->whereRaw('job_service_cat.id = (SELECT MIN(id) FROM job_service_cat WHERE job_id = job.id)');
        })
        ->leftJoin('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
        ->leftJoin('users', 'job.user_id', '=', 'users.id') 
        ->leftJoin('users as workers', 'job.worker_id', '=', 'workers.id')
        ->leftJoin('cities', 'job.city_id', '=', 'cities.id') 
        ->where('job.id', $jobId)
        ->first();
        $district = DB::table('cities')
        ->leftJoin('districts', 'cities.district_id', '=', 'districts.id')
        ->where('cities.id', $job->city_id)
        ->select('districts.name_en as districtName')
        ->first();
        $attachments = DB::table('job_attachments')
        ->where('job_id', $jobId)
        ->select('id', 'img_url', 'job_id')
        ->get();

        $selectedWorker = User::find($job->worker_id);

    return view('admin.joblisting.assign', compact('job','workers','attachments','districts','selectedWorker','district'));
    }
    public function update(Request $request, $jobId)
{

    
    // Retrieve the selected worker ID and status from the request
    $selectedWorkerId = $request->input('selectedWorkerId');
    $selectedDistrictId = $request->input('district');
    $status = 1; // Assuming 1 represents the assigned status

    // Find the job record by ID
    $job = Job::findOrFail($jobId);
    $shouldUpdateStatus = true;

    $existingWorkerId = $job->worker_id;

    $job->worker_id = $request->input('workerId');

    // Check if the selected worker is different from the existing worker
    if ($existingWorkerId != $job->worker_id) {
        $job->status = 1; // Set status to Assigned
    }



    // Check if the job status is 1 or 2
    if ($job->status === 1 || $job->status === 2) {
        // Do not change the status
        $shouldUpdateStatus = false;
    }

    // Retrieve the details of the job being assigned
    $newJobRequiredDate = $job->required_date;
    $newJobRequiredTime = $job->required_time;

   // dd("First".$newJobRequiredTime);
   // dd("First".$newJobRequiredDate);
    // Find overlapping jobs for the selected worker

    $requestedDate = $request->input('requiredDate');
    $requestedTime = $request->input('requiredTime');
    $formattedRequestedDate = Carbon::parse($requestedDate)->format('M d, Y');

    $requestedDayOfWeek = Carbon::parse($requestedDate)->format('l');
//dd("second".$requestedTime );
    // Check if the requested date is a holiday
  // Check if the formatted requested date is a holiday
$isHoliday = DB::table('holiday')->where('date', $formattedRequestedDate)->exists();

    // Determine the rate based on the requested date (holiday or not)
    if ($isHoliday) {
        $referralRate = DB::table('refferal_rates')
            ->where('day', 'Holiday')
            ->value('amount');
        $workerRate = DB::table('worker_rates')
            ->where('day', 'Holiday')
            ->value('amount');
    } else {
        // If not a holiday, determine the rate based on the specific day
        $date = Carbon::parse($requestedDate);
        
        if ($date->isWeekday()) {
            // Weekday: Fetch rates for Monday to Friday
            $referralRate = DB::table('refferal_rates')
                ->where('day', 'Monday to Friday')
                ->value('amount');

            $workerRate = DB::table('worker_rates')
                ->where('day', 'Monday to Friday')
                ->value('amount');
        } elseif ($requestedDayOfWeek === 'Saturday') {
            // Saturday: Fetch rates for Saturday
            $referralRate = DB::table('refferal_rates')
                ->where('day', 'Saturday')
                ->value('amount');

            $workerRate = DB::table('worker_rates')
                ->where('day', 'Saturday')
                ->value('amount');
        } elseif ($requestedDayOfWeek === 'Sunday') {
            // Sunday: Fetch rates for Sunday
            $referralRate = DB::table('refferal_rates')
                ->where('day', 'Sunday')
                ->value('amount');

            $workerRate = DB::table('worker_rates')
                ->where('day', 'Sunday')
                ->value('amount');
        }
    }


    // 2. Update the job_service_cat table with the referral and worker rates
    DB::table('job_service_cat')
        ->where('job_id', $jobId)
        ->update([
            'refferal_amount' => $referralRate,
            'worker_amount' => $workerRate
        ]);






    $overlappingJobs = DB::table('job')
        ->where('worker_id', $selectedWorkerId)
        ->where('id', '<>', $jobId) // Exclude the current job being updated
        ->where(function($query) use ($requestedDate, $requestedTime) {
            $query->whereNotIn('status', [5, 4,3]) // Exclude jobs that are finished (status 5) or paid (status 7)
                ->where('required_date', $requestedDate)
                ->where('required_time', $requestedTime);
        })
        ->exists(); // Check if there are any overlapping jobs

    // If there are overlapping jobs, return an error
    if ($overlappingJobs) {
        return redirect()->back()->with('error', 'The selected worker is already assigned to another job at the same time.');
    }

     // Additional checks for shift assignments
     $morningShift = '8am-12noon';
     $afternoonShift = '1pm-5pm';
     $eveningShift = '4pm-8pm';
 
$morningShift = strtolower(trim($morningShift));
$afternoonShift = strtolower(trim($afternoonShift));
$eveningShift = strtolower(trim($eveningShift));

     // Get the shifts for the new job being assigned
     $isMorningShift = ($requestedTime === $morningShift);
     $isAfternoonShift = ($requestedTime === $afternoonShift);
     $isEveningShift = ($requestedTime === $eveningShift);
 


     Log::info('Requested Time: ' . $requestedTime);
Log::info('Morning Shift: ' . $morningShift);
Log::info('isMorningShift: ' . ($isMorningShift ? 'true' : 'false'));

     // Check for existing jobs assigned to the selected worker on the same date
     $existingJobs = DB::table('job')
         ->where('worker_id', $selectedWorkerId)
         ->where('id', '<>', $jobId) // Exclude the current job being updated
         ->where('required_date', $requestedDate)
         ->whereNotIn('status', [5, 4, 3]) // Exclude finished or paid jobs
         ->get();

     foreach ($existingJobs as $existingJob) {
         $existingTime = $existingJob->required_time;
 
        //  Log::info('Existing Time: ' . $existingTime);
        //  Log::info('isMorningShift: ' . $isMorningShift );
        //  Log::info('isMorningShift2: ' .      $morningShift  );

         // Condition 1: Check if the worker is assigned to the same time
         if ($existingTime === $requestedTime) {
             return redirect()->back()->with('error', 'The selected worker is already assigned to another job at the same time.');
         }
         // Condition 2: Morning shift can only allow one evening shift assignment
        //  if ($isMorningShift && ($existingTime === $afternoonShift || $existingTime === $eveningShift)) {
        //      return redirect()->back()->with('error', 'The selected worker is already assigned to an evening shift.');
        //  }

         // Condition 3: If the worker is assigned to the afternoon shift, they cannot be assigned to the evening shift
         if ($isAfternoonShift && $existingTime === $eveningShift) {
             return redirect()->back()->with('error', 'The selected worker is already assigned to an evening shift.');
         }
 
         // Condition 4: If the worker is assigned to the evening shift, they cannot be assigned to another evening shift
         if ($isEveningShift && ($existingTime === $afternoonShift)) {
             return redirect()->back()->with('error', 'The selected worker is already assigned to another evening shift.');
         }
     }
 

    // Update the job record with the new worker ID and status
    $job->worker_id = $selectedWorkerId;
    $job->worker_area_id = $selectedDistrictId;
   // $job->required_date = $request->input('requiredDate');
   $job->required_date = $requestedDate;
   $job->required_time = $requestedTime;
   // $job->required_time = $request->input('requiredTime');
    if ($shouldUpdateStatus) {
        $job->status = 1; // Assuming you want to set it to assigned status
    }
    $job->save();

    // Redirect back to the job listing page or any other desired page
    return redirect('admin/joblisting')->with('success', 'Worker assigned successfully');
}


public function getWorkersByDistrict($districtId)
{
    // Get the cities that belong to the selected district
    $cityIds = DB::table('cities')
        ->where('district_id', $districtId)
        ->pluck('id');

    // Get the workers that belong to the cities in the selected district
    $workers = User::where('user_type', 3)
        ->where('status', 1)
        ->whereIn('city_id', $cityIds)
        ->get(['id', 'first_name', 'last_name']);

    return response()->json(['workers' => $workers]);
}

public function cancelJob($jobId)
{
    // Find the job record by ID
    $job = Job::findOrFail($jobId);
    
    // Update the job status to 7 and set worker_id to null
    $job->status = 7; // Assuming 7 represents cancelled status
    $job->worker_id = null;
    $job->save();

    // Redirect back to the job listing page with a success message
    return redirect('admin/joblisting')->with('success', 'Job has been cancelled successfully.');
}

    
}
