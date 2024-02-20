<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Job_Service_Cat;
use App\Models\Service_Category;
use App\Models\TimeHours;
use App\Models\RefferalRates;
use App\Models\WokerRates;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JobApiController extends Controller
{

public function createJob(Request $request)
    {
        $userId = Auth::id();

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

        $job = Job::create([
            'user_id' => $userId,
            'description' => $request->input('description'),
            'start_location' => $request->input('start_location'),
            'end_location' => $request->input('end_location'),
            'required_date' => $request->input('required_date'),
            'required_time' => $request->input('required_time'),
            'preferred_sex' => $request->input('preferred_sex'),
        ]);

        // Save selected job categories
        foreach ($request->input('job_categories') as $jobCategoryData) {
            // return response()->json(['status' => 200, 'success' => true, 'message' => 'Job created successfully', 'job' => $jobCategoryData], 201);
            $worker_rate = WokerRates::find($jobCategoryData['refferal_rate_id']);
            Job_Service_Cat::create([
                'service_cat_id' => $jobCategoryData['job_type_id'],
                'job_id' => $job->id,
                'refferal_rate_id' => $jobCategoryData['refferal_rate_id'],
                'refferal_amount' => $jobCategoryData['refferal_amount'],
                'woker_rate_id' => $worker_rate->id,
                'worker_amount' => $worker_rate->amount,
            ]);
        }

        return response()->json(['status' => 200, 'success' => true, 'message' => 'Job created successfully', 'job' => $job], 201);
    }

    public function getServiceList(Request $request)
    {
        
        $service_list = Service_Category::whereNull('deleted_at')
                        ->select('id', 'name', 'description')
                        ->get();

	    return response()->json([
            'status' => 200,
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
            'status' => 200,
	        'success' => true,
	        'message' => 'Records retrieved successfully.',
	        'data' => $job_history,
	    ], 200);
    }

    public function getJobCreatFormData(Request $request)
    {
        $job_type = Service_Category::whereNull('deleted_at')
                    ->select('id', 'name', 'description')
                    ->get();

        $time_hrs = TimeHours::whereNull('deleted_at')
                    ->select('id', 'name')
                    ->get();

        $refferal_rates = RefferalRates::whereNull('deleted_at')
                            ->select('id', 'amount', 'day')
                            ->get();

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Records retrieved successfully.',
            'job_type' => $job_type,
            'time_hrs' => $time_hrs,
            'refferal_rates' => $refferal_rates,
        ], 200);

    }
}