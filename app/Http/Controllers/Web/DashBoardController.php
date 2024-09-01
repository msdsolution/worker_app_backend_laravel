<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashBoardController extends Controller
{
    public function index()
    {  
      $clientsCount = User::where('user_type', 2)->count();
      // $Assignedworkcount = Job::where('status', 1)->count();
      $Pendingworkcount = Job::where('status', 0)->count();
      $Rejectedworkcount = Job::where('status', 6)->count();
      $UnresolvedJobcomplaints = Job::where('complaint_status',1)->Count();

      $currentMonth = now()->month;
      $currentYear = now()->year;
      
      // Query to count jobs created in the current month
      $jobCount = Job::whereYear('created_at', $currentYear)
                     ->whereMonth('created_at', $currentMonth)
                     ->count();
     $Assignedworkcount= Job::where('status', 1) // Status 2 for assigned jobs
     ->whereYear('created_at', $currentYear)
     ->whereMonth('created_at', $currentMonth)
     ->count();

     $Completedworkcount= Job::where('status', 4) // Status 2 for assigned jobs
     ->whereYear('created_at', $currentYear)
     //->whereMonth('created_at', $currentMonth)
     ->count();
     $CompletedworkPaidcount= Job::where('status', 5) // Status 2 for assigned jobs
     ->whereYear('created_at', $currentYear)
     //->whereMonth('created_at', $currentMonth)
     ->count();

      return view('admin.dashboard', compact('jobCount','clientsCount','Assignedworkcount','Pendingworkcount','Rejectedworkcount','Completedworkcount','CompletedworkPaidcount','UnresolvedJobcomplaints'));
    }
    
    public function assignedwokr()
    {
  //     $jobDetails = DB::table('job')
  //       ->select(
  //           'job.id as jobId',
  //           'job.user_id',
  //           'job.job_no',
  //           'users.first_name as ClientFirstName',
  //           'users.last_name as ClientLastName',
  //           'job.description as jobDescription',
  //           'job.city_id',
  //           'cities.name_en as cityName',
  //           'job.start_location',
  //           'job.end_location',
  //           'job.worker_id',
  //           'workers.first_name as workerFirstName',  // Worker first name
  //           'workers.last_name as workerLastName', 
  //           'job.status',
  //           'service_cat.name as serviceName',
  //           'service_cat.description as serviceDescription',
  //           'job.required_date',
  //           'job.required_time',
  //           'job.preferred_sex'
  //       )
  //       ->leftJoin('job_service_cat', function ($join) {
  //           $join->on('job.id', '=', 'job_service_cat.job_id')
  //               ->whereRaw('job_service_cat.id = (SELECT MIN(id) FROM job_service_cat WHERE job_id = job.id)');
  //       })
  //       ->leftJoin('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
  //       ->leftJoin('users', 'job.user_id', '=', 'users.id')
  //       ->leftJoin('users as workers', 'job.worker_id', '=', 'workers.id')
  //       ->leftJoin('cities', 'job.city_id', '=', 'cities.id')
  //       ->where('job.status', '=', 1)  // Filter for status = 1
  //       ->orderByRaw('CASE WHEN job.status = 6 THEN 0 ELSE 1 END, job.created_at DESC')
  //       ->get();

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
  ->where('job.status', '=', 1)  // Filter for status = 1
  ->whereMonth('job.created_at', '=', date('m')) // Filter for current month
  ->whereYear('job.created_at', '=', date('Y'))  // Filter for current year
  ->orderByRaw('CASE WHEN job.status = 6 THEN 0 ELSE 1 END, job.created_at DESC')
  ->get();

return view('admin.joblisting.index', compact('jobDetails'));
  }
  public function pendingwork()
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
      ->where('job.status', '=', 0)  // Filter for status = 1
      ->orderByRaw('CASE WHEN job.status = 6 THEN 0 ELSE 1 END, job.created_at DESC')
      ->get();

return view('admin.joblisting.index', compact('jobDetails'));
}
public function rejectedwork()
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
    ->where('job.status', '=', 6)  // Filter for status = 1
    ->orderByRaw('CASE WHEN job.status = 6 THEN 0 ELSE 1 END, job.created_at DESC')
    ->get();

return view('admin.joblisting.index', compact('jobDetails'));
}
public function completedworkunpaid()
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
    ->where('job.status', '=', 4)  // Filter for status = 1
    ->orderByRaw('CASE WHEN job.status = 6 THEN 0 ELSE 1 END, job.created_at DESC')
    ->get();

return view('admin.joblisting.index', compact('jobDetails'));
}
public function completedworkpaid()
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
    ->where('job.status', '=', 5)  // Filter for status = 1
    ->orderByRaw('CASE WHEN job.status = 6 THEN 0 ELSE 1 END, job.created_at DESC')
    ->get();

return view('admin.joblisting.index', compact('jobDetails'));
}
public function Unresolvedcomplaint()
{
    // $jobsWithComplaints = DB::table('job')
    // ->where('is_complaint', 1)
    // ->pluck('job_no'); // Retrieve job numbers only
    $jobsWithComplaints = DB::table('job')
    ->where('complaint_status', 1)
    ->orderBy('updated_at', 'desc') 
    ->get(['id', 'job_no', 'complaint_status']); // Fetch objects with both 'id' and 'job_no'

return view('admin.complaint.index', ['jobs' => $jobsWithComplaints]);

//  return view('admin.complaint.index', ['jobs' => $jobsWithComplaints]);
}
}
