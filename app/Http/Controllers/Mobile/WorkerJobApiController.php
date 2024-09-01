<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Mobile\FCMApiController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Job_Service_Cat;
use App\Models\Service_Category;
use App\Models\TimeHours;
use App\Models\RefferalRates;
use App\Models\WokerRates;
use App\Models\JobAttachment;
use App\Models\worker_feedback;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class WorkerJobApiController extends Controller
{

    // protected $fcmservice;

    // public function __construct(FCMApiController $fcmservice)
    // {
    //     $this->fcmservice = $fcmservice;
    // }

	public function getWorkerJobList(Request $request)
    {
        
       $user = auth()->user();

       $job_statuses = [1];

       $job_history = Job::where('worker_id', $user->id)
       				  ->whereIn('status', $job_statuses)
                      ->orderBy('job.created_at', 'desc')
        			  ->get();

	    return response()->json([
            'status' => 200,
	        'success' => true,
	        'message' => 'Records retrieved successfully.',
	        'data' => $job_history,
	    ], 200);
    }

    public function getWorkerAcceptedAndStartedJobList(Request $request)
    {
        
       $user = auth()->user();

       $job_statuses = [2, 3];

       $job_history = Job::where('worker_id', $user->id)
                      ->whereIn('status', $job_statuses)
                      ->orderBy('job.updated_at', 'desc')
                      ->get();

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Records retrieved successfully.',
            'data' => $job_history,
        ], 200);
    }

    public function getWorkerFinishedJobList(Request $request)
    {
        
       $user = auth()->user();

        $perPage = $request->query('per_page', 10); // Number of items per page
        $page = $request->query('page', 1); // Current page

        // $query = Job::where('worker_id', $user->id)
        //               ->where('status', 4);
        $job_statuses = [4, 5];
        $query = Job::leftJoin('worker_payment', 'job.id', '=', 'worker_payment.job_id')
            ->leftJoin('worker_payment_attachment', 'worker_payment.id', '=', 'worker_payment_attachment.worker_payment_id')
            ->where('job.status', $job_statuses)  // Filter jobs with status 4
            ->where('job.worker_id', $user->id)  // Ensure worker_id matches
            ->select('job.*',  DB::raw('CONCAT("' . url('storage') . '/", worker_payment_attachment.file_path) as worker_payment_attachment_url'), 'worker_payment.amount', DB::raw('COALESCE(worker_payment.status, 0) as worker_payment_status'))
            ->orderBy('job.created_at', 'desc');

        // Search query
        if ($request->filled('search')) {
            $searchTerm = $request->query('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('job_no', 'like', "%$searchTerm%")
                      ->orWhere('description', 'like', "%$searchTerm%");
            });
        }

        // Retrieve paginated results
        $job_history = $query->paginate($perPage);

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Records retrieved successfully.',
            'data' => $job_history,
        ], 200);
    }

    public function acceptJob($id)
    {
        $job = Job::findOrFail($id);
        $job->status = 2;
        $job->save();

        $refferal_user = User::findOrFail($job->user_id);

        $data = ['device_token' => $refferal_user->fcm_token,
                'title' => 'Accepted',
                'body' => 'Worker Accepted the job.'
                ];
        //$this->fcmservice->sendPushNotification($data);

        return response()->json([
        	'status' => 200,
	        'success' => true,
        	'message' => 'Job accepted successfully',
        ], 200);
    }

    public function rejectJob($id)
    {
        $job = Job::findOrFail($id);
        $job->status = 6;
        $job->save();

        $refferal_user = User::findOrFail($job->user_id);

        $data = ['device_token' => $refferal_user->fcm_token,
                'title' => 'Rejected',
                'body' => 'Worker rejected the job. Will assign new worker.'
                ];
        //$this->fcmservice->sendPushNotification($data);

        return response()->json([
        	'status' => 200,
	        'success' => true,
        	'message' => 'Job rejected successfully',
        ], 200);
    }

    public function startJob($id)
    {
        $user = auth()->user();
        $exists = Job::where('worker_id', $user->id)
                      ->where('status', 3)
                      ->exists();

        if ($exists) {
         return response()->json([
                        'status' => 200,
                        'success' => true,
                        'message' => 'Already one job is started. Please finish the job to start new job.',
                    ], 200);
        } else {
            $job = Job::findOrFail($id);
            $job->status = 3;
            $job->save();

            $refferal_user = User::findOrFail($job->user_id);

            $data = ['device_token' => $refferal_user->fcm_token,
                    'title' => 'Started',
                    'body' => 'Worker started the job.'
                    ];
            //$this->fcmservice->sendPushNotification($data);

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Job started successfully',
            ], 200);
        }
    }

    public function finishJob(Request $request)
    {

        $request->validate([
            'job_id' => 'required',
            'finish_job_description',
            'files.*' => 'nullable|file|mimes:jpeg,png,gif|max:20000',
        ]);

        $job = Job::findOrFail($request->job_id);
        $job->finishJobDescription = $request->finish_job_description;
        $job->status = 4;
        $job->save();

        $refferal_user = User::findOrFail($job->user_id);

        $data = ['device_token' => $refferal_user->fcm_token,
                'title' => 'Finished',
                'body' => 'Worker finished the job.'
                ];
        //$this->fcmservice->sendPushNotification($data);

        //Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {

                $path = $file->store('jobAttachment', 'public');

                // Save file path to database
                JobAttachment::create([
                    'job_id' => $job->id,
                    'img_url' => $path,
                ]);
            }
        }

        return response()->json([
        	'status' => 200,
	        'success' => true,
        	'message' => 'Job finished successfully',
        ], 200);
    }

    public function extendJob(Request $request, $job_id)
    {

        $validatedData = $request->validate([
            'extended_hr' => 'required|integer|min:1', // Example validation rules
        ]);

        try {

            $job = Job::findOrFail($job_id);
            $job->is_extended = 1;
            $job->extended_hrs = $request->extended_hr;
            $job->save();

            $refferal_user = User::findOrFail($job->user_id);

            $data = ['device_token' => $refferal_user->fcm_token,
                    'title' => 'Extended',
                    'body' => 'Worker extended the job.'
                    ];
            //$this->fcmservice->sendPushNotification($data);

            return response()->json(['status' => 200, 'success' => true, 'message' => 'Job extended successfully'], 200);

        } catch (\Exception $e) {
            // Handle any exceptions (e.g., job not found)
            return response()->json(['status' => 500, 'success' => false, 'message' => 'Failed to update extended status', 'error' => $e->getMessage()], 500);
        }
    }

    public function workerFeedback(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'job_id' => 'required',
            'message' => 'required',
            'rating' => 'required',
            'worker_id' => 'required',
        ]);

        worker_feedback::create([
                    'job_id' => $request->job_id,
                    'refferal_id' => $user->id,
                    'user_id' => $request->worker_id,
                    'message' => $request->message,
                    'ratings' => $request->rating,
                    'status' => 0,
                ]);

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Feedback Submitted successfully',
        ], 200);
    }
}