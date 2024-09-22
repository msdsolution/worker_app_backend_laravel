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

class JobListingController extends Controller
{
    public function index()
    {
    //     $jobDetails = DB::table('job')
    //     ->select(
    //         'job.id as jobId', // Selecting the id from the job table
    //         'job.user_id',
    //         'users.first_name as userFirstName',
    //         'job.description as jobDescription',
    //         'job.city_id',
    //         'cities.name_en as cityName',
    //         'job.start_location',
    //         'job.end_location',
    //         'job.worker_id',
    //         'workers.first_name as workerName',
    //         'job.status',
    //         'service_cat.name as serviceName',
    //         'service_cat.description as serviceDescription',
    //         'job.required_date',
    //         'job.required_time',
    //         'job.preferred_sex'
    //     )
    //     ->leftJoin('job_service_cat', function ($join) {
    //         $join->on('job.id', '=', 'job_service_cat.job_id')
    //             ->whereRaw('job_service_cat.id = (SELECT MIN(id) FROM job_service_cat WHERE job_id = job.id)');
    //     })
    //     ->leftJoin('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
    //     ->leftJoin('users', 'job.user_id', '=', 'users.id') 
    //     ->leftJoin('users as workers', 'job.worker_id', '=', 'workers.id')
    //     ->leftJoin('cities', 'job.city_id', '=', 'cities.id') 
    //     ->get();// Use get() to retrieve all results
    // // Pass the data to the view
    // return view('admin.joblisting.index', compact('jobDetails'));
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
    // public function update(Request $request, $jobId)
    // {
    // // Retrieve the selected worker ID and status from the request
    // $selectedWorkerId = $request->input('selectedWorkerId');
    // $status = 1; // Assuming 1 represents the assigned status

    // // Find the job record by ID
    // $job = Job::findOrFail($jobId);

    // // Update the worker_id and status columns
    // $job->worker_id = $selectedWorkerId;
    // $job->status = $status;

    // // Save the changes to the database
    // $job->save();

    // // Redirect back to the job listing page or any other desired page
    // return redirect('admin/joblisting')->with('success', 'Worker assigned successfully');
    // }
    public function update(Request $request, $jobId)
{
    // Retrieve the selected worker ID and status from the request
    $selectedWorkerId = $request->input('selectedWorkerId');
    $selectedDistrictId = $request->input('district');
    $status = 1; // Assuming 1 represents the assigned status

    // Find the job record by ID
    $job = Job::findOrFail($jobId);

    // Retrieve the details of the job being assigned
    $newJobRequiredDate = $job->required_date;
    $newJobRequiredTime = $job->required_time;

    // Find overlapping jobs for the selected worker
    $overlappingJobs = DB::table('job')
        ->where('worker_id', $selectedWorkerId)
        ->where('id', '<>', $jobId) // Exclude the current job being updated
        ->where(function($query) use ($newJobRequiredDate, $newJobRequiredTime) {
            $query->whereNotIn('status', [5, 4,3]) // Exclude jobs that are finished (status 5) or paid (status 7)
                ->where('required_date', $newJobRequiredDate)
                ->where('required_time', $newJobRequiredTime);
        })
        ->exists(); // Check if there are any overlapping jobs

    // If there are overlapping jobs, return an error
    if ($overlappingJobs) {
        return redirect()->back()->with('error', 'The selected worker is already assigned to another job at the same time.');
    }

    // Update the job record with the new worker ID and status
    $job->worker_id = $selectedWorkerId;
    $job->worker_area_id = $selectedDistrictId;
    $job->status = $status;
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

    
}
