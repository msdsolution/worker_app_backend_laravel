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
use App\Models\ComplaintMessages;
use App\Models\ComplaintAttachment;
use App\Models\worker_feedback;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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
            'job_categories.*.refferal_rate_id' => 'required',
            'job_categories.*.refferal_amount' => 'required',
        ]);

        try {
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

            $jobId = $job->id;
            $jobIdLength = strlen($jobId);

            // Generate job number in format #00000 with job ID appended
            if ($jobIdLength <= 5) {
                $jobNumber = '#' . str_pad($jobId, 5, '0', STR_PAD_LEFT);
                $job->job_no = $jobNumber;
                $job->save();
            } else {
                $jobNumber = '#' . $jobId;
                $job->job_no = $jobNumber;
                $job->save();
            }

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

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
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

       $perPage = $request->query('per_page', 10); // Number of items per page
        $page = $request->query('page', 1); // Current page

        $jobs = Job::with('jobType.serviceCat')->where('user_id', $user->id)->orderBy('job.created_at', 'desc');

        // Search query
        if ($request->filled('search')) {
            $searchTerm = $request->query('search');
            $jobs->where(function ($jobs) use ($searchTerm) {
                $jobs->where('job_no', 'like', "%$searchTerm%")
                      ->orWhere('description', 'like', "%$searchTerm%");
            });
        }

        // Retrieve paginated results
        $jobs = $jobs->paginate($perPage);

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
	        'data' => [
                'current_page' => $jobs->currentPage(),
                'jobs' => $modifiedJobs,
                'first_page_url' => $jobs->url(1),
                'from' => $jobs->firstItem(),
                'last_page' => $jobs->lastPage(),
                'last_page_url' => $jobs->url($jobs->lastPage()),
                'links' => $jobs->linkCollection()->toArray(),
                'next_page_url' => $jobs->nextPageUrl(),
                'path' => $jobs->path(),
                'per_page' => $jobs->perPage(),
                'prev_page_url' => $jobs->previousPageUrl(),
                'to' => $jobs->lastItem(),
                'total' => $jobs->total(),
            ],
	    ], 200);
    }

    public function getJobDetail($id)
    {
        $user = auth()->user();

        $jobs = Job::with(['jobType.serviceCat', 'worker', 'complaint', 'jobAttachments'])->findOrFail($id);

        $worker_feedback = null;

        
        // Add worker_name to the job data
        if ($jobs->worker_id != null) {
            $jobs->worker_name = $jobs->worker->first_name;

            $worker_feedback = worker_feedback::where('user_id', $jobs->worker_id)->where('status', 1)->latest()->take(5)->get();

        } else {
            $jobs->worker_name = "Not Assigned";
        }

        // Add complaint status to the job data
        if ($jobs->complaint != null) {
            $jobs->complaint_status = $jobs->complaint->status;

        } else {
            $jobs->complaint_status = 0;
        }

        $jobs->worker_feedback = $worker_feedback;

        // Include the referral name in the feedback
        foreach ($jobs->worker_feedback as $feedback) {
            $feedback->refferal_name = $feedback->refferal_id ? User::findOrFail($feedback->refferal_id)->first_name : null;
        }

        unset($jobs->worker);
        unset($jobs->complaint);

         if ($jobs->user_id !== $user->id && $jobs->worker_id !== $user->id) {
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

    // public function submitJobComplaint(Request $request)
    // {
    //     $userId = Auth::id();

    //     $request->validate([
    //         'job_id' => 'required',
    //         'message' => 'required',
    //         'files.*' => 'file|mimes:jpeg,png,gif|max:20000',
    //     ]);

    //     // try {
    //     //     DB::beginTransaction();

    //         $job = Job::findOrFail($request->job_id);

    //         if ($job && $job->is_complaint == 0) {
    //             $job->is_complaint = 1;
    //             $job->complaint_status = 0;
    //             $job->save();
    //         }

    //         $complaint_message = ComplaintMessages::create([
    //             'job_id' => $job->id,
    //             'user_id' => $userId,
    //             'message' => $request->message,
    //         ]);

    //         //return $request->hasFile('files');

    //         // Handle file uploads
    //         if ($request->hasFile('files')) {
    //             foreach ($request->file('files') as $file) {
    //                 // Ensure the directory exists or create it
    //                 $directory = 'complaintAttachment';
    //                 if (!Storage::exists($directory)) {
    //                     Storage::makeDirectory($directory);
    //                 }

    //                 // Store the file in the specified directory
    //                 $path = $file->store($directory);

    //                 // Save file path to database
    //                 ComplaintAttachment::create([
    //                     'job_id' => $job->id,
    //                     'img_url' => $path,
    //                     'complaint_message_id' => $complaint_message->id,
    //                 ]);
    //             }
    //         }

    //         return response()->json([
    //             'status' => 200,
    //             'success' => true,
    //             'message' => 'Complaint created successfully',
    //             'complaint' => $complaint_message,
    //         ], 201);

    //     // } catch (\Exception $e) {
    //     //     DB::rollBack();

    //     //     return response()->json([
    //     //         'status' => 500,
    //     //         'success' => false,
    //     //         'message' => 'Failed to create complaint: ' . $e->getMessage(),
    //     //     ], 500);
    //     // }
    // }

    public function submitJobComplaint(Request $request)
    {
        $userId = Auth::id();

        // Custom validation rule to ensure at least one of 'message' or 'files' is present
        $request->validate([
            'job_id' => 'required|exists:job,id',
            'message' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:jpeg,png,gif|max:20000',
        ], [
            'message.required_without' => 'The message field is required when no files are uploaded.',
            'files.required_without' => 'At least one file is required when no message is provided.',
        ]);
        // Check if either 'message' or 'files' is present
        if (!$request->filled('message') && !$request->hasFile('files')) {
            return response()->json([
                'status' => 422,
                'success' => false,
                'message' => 'At least one of message or file must be provided.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Find the job
            $job = Job::findOrFail($request->job_id);

            if ($job && $job->is_complaint == 0) {
                $job->is_complaint = 1;
                $job->complaint_status = 1;
                $job->save();
            }

            // Create the complaint message if 'message' is provided
            $complaint_message = null;
            if ($request->filled('message')) {
                $complaint_message = ComplaintMessages::create([
                    'job_id' => $job->id,
                    'user_id' => $userId,
                    'message' => $request->message,
                ]);
            }

            // Handle file uploads if 'files' are provided
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    if ($file->isValid()) {
                        // Store the file in the specified directory
                        $path = $file->store('complaintAttachment', 'public');

                        // Save file path to database
                        ComplaintAttachment::create([
                            'job_id' => $job->id,
                            'img_url' => $path,
                            'complaint_message_id' => $complaint_message ? $complaint_message->id : null,
                        ]);
                    } else {
                        // Handle invalid file situation
                        return response()->json([
                            'status' => 422,
                            'success' => false,
                            'message' => 'One or more files are invalid.',
                        ], 422);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Complaint created successfully',
                'complaint' => $complaint_message,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Failed to create complaint: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function getAllJobComplaintsWithMessages($jobId)
    {
        $jobComplaints = Job::leftJoin('complaint_messages', 'job.id', '=', 'complaint_messages.job_id')
                ->leftJoin('complaint_attachments', 'complaint_messages.id', '=', 'complaint_attachments.complaint_message_id')
             ->where('job.id', $jobId)
             ->select('job.id','job.is_complaint','job.complaint_status',
                'complaint_messages.id as message_id',
                'complaint_messages.user_id',
                'complaint_messages.message',
                DB::raw('CONCAT("' . url('storage') . '/", complaint_attachments.img_url) as full_img_url'))
             ->get();

        return response()->json([
            'status' => 200,
            'success' => true,
            'data' => $jobComplaints,
        ]);
    }

    public function getJobPayment($jobId){
        $user = auth()->user();

        try {
                $job = Job::with(['worker', 'jobServiceCat'])
                    ->where('id', $jobId)
                    ->where('status', 4)  // Filter by status 4
                    ->first();

                    // Check if job was found and has the correct status
                if (!$job) {
                    return response()->json([
                        'status' => 404,
                        'success' => false,
                        'message' => 'Job not found or status is not 4.',
                    ], 404);
                }

                $referalAmount = DB::table('job_service_cat')
                ->select('refferal_amount')
                ->where('job_id', $jobId)
                ->first();

                $extendedHourAmount = 0;
                $job->extendedHrAmount = 0;

                // If the job is extended, calculate the extended hour amount
                if ($job->is_extended == 1) {
                    // Get the amount for the extended hours
                    $extendedHourRate = DB::table('extended_hour')
                        ->select('amount')
                        ->first(); // Assuming amount is constant for all extended hours
                    
                    // Calculate extended hour amount
                    if ($extendedHourRate) {
                        $extendedHourAmount = $extendedHourRate->amount * $job->extended_hrs;
                        $job->extendedHrAmount = $extendedHourAmount ?? 0;
                    }
                }

                // Calculate grand total
                $grandTotal = ($referalAmount->refferal_amount ?? 0) + $extendedHourAmount;
                $job->grandTotal = $grandTotal;

                return response()->json([
                    'status' => 200,
                    'success' => true,
                    'message' => 'Records retrieved successfully.',
                    'data' => $job,
                ], 200);

        } catch (\Exception $e) {
            // Handle any other potential exceptions
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'An unexpected error occurred.'.$e,
            ], 500);
        }
    }
}