<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Job_Service_Cat;
use App\Models\Service_Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class JobApiController extends Controller
{

public function createJob(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'start_location' => 'required',
            'end_location' => 'required',
            'required_date' => 'required',
            'required_time' => 'required',
            'preferred_sex' => 'required',
            'job_categories' => 'required|array',
            'job_categories.*.name' => 'required',
            'job_categories.*.job_type_id' => 'required',
        ]);

 

        $job = Job::create($request->except('job_categories'));

        // Save selected job categories
        foreach ($request->input('job_categories') as $jobCategoryData) {
            Job_Service_Cat::create([
                'service_cat_id' => $jobCategoryData['job_type_id'],
                'job_id' => $job->id,
            ]);
        }

        return response()->json(['message' => 'Job created successfully', 'job' => $job], 201);
    }

    public function getServiceList(Request $request)
    {
        
        $service_list = Service_Category::whereNull('deleted_at')
        ->select('id', 'name', 'description')
        ->get();

	    return response()->json([
	        'success' => true,
	        'message' => 'Records retrieved successfully.',
	        'data' => $service_list,
	    ], 200);
    }

    public function getJobHistoryList(Request $request)
    {
        
       $user = auth()->user();


       $job_history = Job::with('jobType')
       	->where('user_id', $user->id)
        ->get();

	    return response()->json([
	        'success' => true,
	        'message' => 'Records retrieved successfully.',
	        'data' => $job_history,
	    ], 200);
    }
}