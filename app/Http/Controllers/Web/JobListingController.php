<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Job_Service_Cat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'users.first_name as userFirstName',
            'job.description as jobDescription',
            'job.city_id',
            'cities.name_en as cityName',
            'job.start_location',
            'job.end_location',
            'job.worker_id',
            'workers.first_name as workerName',
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
        ->orderByRaw('CASE WHEN job.status = 6 THEN 0 ELSE 1 END, job.id')
       // ->paginate(10); 
        ->get();

    return view('admin.joblisting.index', compact('jobDetails'));
    }
    public function assign($jobId)
    {
        $workers = User::where('user_type', 3)
        ->where('status', 1) // Add this line to filter by status
        ->get();
        $job = DB::table('job')
        ->select(
            'job.id as jobId',
            'job.user_id',
            'users.first_name as userFirstName',
            'job.description as jobDescription',
            'job.city_id',
            'cities.name_en as cityName',
            'job.start_location',
            'job.end_location',
            'job.worker_id',
            'workers.first_name as workerName',
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
        ->where('job.id', $jobId)
        ->first();

    return view('admin.joblisting.assign', compact('job','workers'));
    }
    public function update(Request $request, $jobId)
    {
    // Retrieve the selected worker ID and status from the request
    $selectedWorkerId = $request->input('selectedWorkerId');
    $status = 1; // Assuming 1 represents the assigned status

    // Find the job record by ID
    $job = Job::findOrFail($jobId);

    // Update the worker_id and status columns
    $job->worker_id = $selectedWorkerId;
    $job->status = $status;

    // Save the changes to the database
    $job->save();

    // Redirect back to the job listing page or any other desired page
    return redirect('admin/joblisting')->with('success', 'Worker assigned successfully');
    }
    
}
