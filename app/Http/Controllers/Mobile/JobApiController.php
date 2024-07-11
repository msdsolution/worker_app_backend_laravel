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
use App\Models\SriLankaDistricts;
use App\Models\JobComplaint;
use App\Models\ComplaintMessages;
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
            'city_id' => 'required',
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
            'city_id' => $request->input('city_id'),
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


       // $job_history = Job::with('jobType')
       // 	->where('user_id', $user->id)
       //  ->get();

        $jobs = Job::with('jobType.serviceCat')->where('user_id', $user->id)->get();

        // Modify the structure of the data to append service_cat name inside job_type
           $modifiedJobs = $jobs->map(function ($job) {
                if ($job->jobType) {
                    foreach ($job->jobType as $jobType) {
                        $jobType->service_cat_name = $jobType->serviceCat->name;
                        unset($jobType->serviceCat); // Remove the serviceCat object if needed
                    }
                }
                return $job;
            });

	    return response()->json([
            'status' => 200,
	        'success' => true,
	        'message' => 'Records retrieved successfully.',
	        'data' => $modifiedJobs,
	    ], 200);
    }

    public function getJobDetail($id){
        $user = auth()->user();

        $jobs = Job::with('jobType.serviceCat')->findOrFail($id);

         if ($jobs->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Records retrieved successfully.',
            'data' => $jobs,
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

        // $refferal_rates = RefferalRates::whereNull('deleted_at')
        //                     ->select('id', 'amount', 'day')
        //                     ->get();

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Records retrieved successfully.',
            'job_type' => $job_type,
            'time_hrs' => $time_hrs,
            //'refferal_rates' => $refferal_rates,
        ], 200);

    }

    public function submitJobComplaint(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'job_id' => 'required',
            'message' => 'required',
        ]);

        $jobComplaint = JobComplaint::where('job_id', $request->job_id)->first();

        if (!$jobComplaint) {
            $jobComplaint = JobComplaint::create([
                'job_id' => $request->job_id,
            ]);
        }

        $complaint_message = ComplaintMessages::create([
            'complaint_id' => $jobComplaint->id,
            'user_id' => $userId,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Complaint created successfully',
            'complaint' => $complaint_message,
        ], 201);
    }

    public function getAllJobComplaintsWithMessages($jobId)
    {
        $jobComplaints = JobComplaint::leftJoin('complaint_messages', 'job_complaint.id', '=', 'complaint_messages.complaint_id')
                                     ->where('job_complaint.job_id', $jobId)
                                     ->select('job_complaint.*', 'complaint_messages.id as message_id', 'complaint_messages.user_id', 'complaint_messages.message')
                                     ->get();

        return response()->json([
            'status' => 200,
            'success' => true,
            'data' => $jobComplaints,
        ]);
    }
}