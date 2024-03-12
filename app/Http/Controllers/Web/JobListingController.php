<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Job_Service_Cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobListingController extends Controller
{
    //
    // public function index(Job_Service_Cat $jobServiceCatId)
    // {
    //     $jobServiceCat = Job_Service_Cat::with(['serviceCat', 'job'])
    //     ->find($jobServiceCatId);

    // $serviceName = $jobServiceCat->serviceCat->name;
    // $serviceDescription = $jobServiceCat->serviceCat->description;
    
    // $requiredDate = $jobServiceCat->job->required_date;
    // $requiredTime = $jobServiceCat->job->required_time;
    // $preferredSex = $jobServiceCat->job->preferred_sex;
    
    // $startLocation = $jobServiceCat->job->start_location;
    // $endLocation = $jobServiceCat->job->end_location;
    // // Now you can use these variables as needed in your view
    // return view('admin.joblisting.index', compact(
    //     'serviceName',
    //     'serviceDescription',
    //     'requiredDate',
    //     'requiredTime',
    //     'preferredSex',
    //     'startLocation',
    //     'endLocation'
    // ));
    // }

    // public function showDetails($jobServiceCatId)
    // {
    //     // Use Query Builder to retrieve job details
    //     $jobDetails = DB::table('job_service_cat')
    //         ->join('jobs', 'job_service_cat.job_id', '=', 'jobs.id')
    //         ->join('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
    //         ->select(
    //             'service_cat.name as serviceName',
    //             'service_cat.description as serviceDescription',
    //             'jobs.required_date',
    //             'jobs.required_time',
    //             'jobs.preferred_sex',
    //             'jobs.start_location',
    //             'jobs.end_location'
    //         )
    //         ->where('job_service_cat.id', $jobServiceCatId)
    //         ->first(); // Use first() to retrieve a single result

    //     // Check if job details are found
    //     if (!$jobDetails) {
    //         abort(404); // or handle it in a way that suits your application
    //     }

    //     // Now you can access the data
    //     $serviceName = $jobDetails->serviceName;
    //     $serviceDescription = $jobDetails->serviceDescription;
    //     $requiredDate = $jobDetails->required_date;
    //     $requiredTime = $jobDetails->required_time;
    //     $preferredSex = $jobDetails->preferred_sex;
    //     $startLocation = $jobDetails->start_location;
    //     $endLocation = $jobDetails->end_location;

    //     // Pass the data to the view
    //     return view('admin.joblisting.index', compact(
    //         'serviceName',
    //         'serviceDescription',
    //         'requiredDate',
    //         'requiredTime',
    //         'preferredSex',
    //         'startLocation',
    //         'endLocation'
    //     ));
    // }
    public function index()
    {
        // Use Query Builder to retrieve job details
        // $jobDetails = DB::table('job_service_cat')
        //     ->join('job', 'job_service_cat.job_id', '=', 'job.id')
        //     ->join('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
        //     ->select(
        //         'service_cat.name as serviceName',
        //         'service_cat.description as serviceDescription',
        //         'job.required_date',
        //         'job.required_time',
        //         'job.preferred_sex',
        //         'job.start_location',
        //         'job.end_location'
        //     )
        //     ->get(); // Use get() to retrieve all results
    
        // // Pass the data to the view
        // return view('admin.joblisting.index', compact('jobDetails'));
        $jobDetails = DB::table('job_service_cat')
        ->join('job', 'job_service_cat.job_id', '=', 'job.id')
        ->join('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
        ->select(
            //'job_service_cat.id',
            'job.id as jobId', // Use 'job.id' as 'jobId'
            'service_cat.name as serviceName',
            'service_cat.description as serviceDescription',
            'job.required_date',
            'job.required_time',
            'job.preferred_sex',
            'job.start_location',
            'job.end_location'
        )
        ->get(); // Use get() to retrieve all results
    // Pass the data to the view
    return view('admin.joblisting.index', compact('jobDetails'));
    }
    
}
