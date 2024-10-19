<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class WorkerJobApiController extends Controller
{

    protected $fcmservice;

    public function __construct(FCMApiController $fcmservice)
    {
        $this->fcmservice = $fcmservice;
    }

	public function getWorkerJobList(Request $request)
    {
        
       $user = auth()->user();

       if ($user->is_verified == 1) {
            $job_statuses = [1];

           $job_history = Job::where('worker_id', $user->id)
                          ->whereIn('status', $job_statuses)
                          ->orderBy('job.created_at', 'desc')
                          ->get();

            // Retrieve WORKER amount associated with the job
            // $workerAmount = DB::table('job_service_cat')
            //     ->select('worker_amount')
            //     ->where('job_id', $job_data->id)
            //     ->first();

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Records retrieved successfully.',
                'data' => $job_history,
            ], 200);
       } else {

       }
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

       if ($user->is_verified == 1) {
            $perPage = $request->query('per_page', 10); // Number of items per page
            $page = $request->query('page', 1); // Current page

            // $query = Job::where('worker_id', $user->id)
            //               ->where('status', 4);
            //if ($user->is_verified == 1) {
                 $job_statuses = [4, 5];
                $query = Job::leftJoin('worker_payment', 'job.id', '=', 'worker_payment.job_id')
                    ->leftJoin('worker_payment_attachment', 'worker_payment.id', '=', 'worker_payment_attachment.worker_payment_id')
                    ->whereIn('job.status', $job_statuses)  // Filter jobs with status 4
                    ->where('job.worker_id', $user->id)  // Ensure worker_id matches
                    ->select('job.*',  DB::raw('CONCAT("' . url('storage') . '/", worker_payment_attachment.file_path) as worker_payment_attachment_url'), 'worker_payment.amount', DB::raw('COALESCE(worker_payment.status, 0) as worker_payment_status'))
                    ->orderBy('job.created_at', 'desc');
            // } else {
            //     $query = [];
            // }

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
       } else {
             return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Records retrieved successfully.',
                'data' => [
                    'current_page' => 1,
                    'data' => [],
                    'first_page_url' => "http://localhost/worker_app_backend_laravel/public/api/getWorkerFinishedJobList?page=1",
                    'from' => null,
                    'last_page' => 1,
                    'last_page_url' => "http://localhost/worker_app_backend_laravel/public/api/getWorkerFinishedJobList?page=1",
                    'links' => [],
                    'next_page_url' => null,
                    'path' => "http://localhost/worker_app_backend_laravel/public/api/getWorkerFinishedJobList",
                    'per_page' => 10,
                    'prev_page_url' => null,
                    'to' => null,
                    'total' => 0,
                ],
            ], 200);
       }
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

        $this->sendInvoice($job);

        return response()->json([
        	'status' => 200,
	        'success' => true,
        	'message' => 'Job finished successfully',
        ], 200);
    }

    public function sendInvoice(Job $job_data)
    {
        $refferal = User::findOrFail($job_data->user_id);
        $clientName = $refferal->first_name." ".$refferal->last_name;
        $clientEmail = $refferal->email;
        $message = 'Job has been completed by the worker. Please review the attached invoice and do the payment through mobile app.';


        // Retrieve job details
        $job = DB::table('job')
            ->select(
                'job.id as jobId',
                'job_no',
                'job.user_id',
                'users.first_name as userFirstName',
                'users.last_name as userLastName',
                'users.email as Email',
                'users.user_address as Address',
                'users.phone_no as Phonenumber',
                'job.description as jobDescription',
                'job.city_id',
                'cities.name_en as cityName',
                'job.start_location',
                'job.end_location',
                'job.worker_id',
                'workers.first_name as workerName',
                'job.status',
                'job.required_date',
                'job.required_time',
                'job.created_at',
                'job.preferred_sex',
                'job.is_extended',
                'job.extended_hrs',
                'job.is_worker_tip',
                'job.worker_tip_amount',
                'job.is_travelled',
                'job.travelled_km'
            )
            ->leftJoin('users', 'job.user_id', '=', 'users.id')
            ->leftJoin('users as workers', 'job.worker_id', '=', 'workers.id')
            ->leftJoin('cities', 'job.city_id', '=', 'cities.id')
            ->where('job.id', $job_data->id)
            ->first();

        // Retrieve all service categories associated with the job
        $serviceCategories = DB::table('job_service_cat')
            ->join('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
            ->select('service_cat.name')
            ->where('job_service_cat.job_id', $job_data->id)
            ->get();

        // Concatenate service category names into a single string
        $categoryNames = $serviceCategories->pluck('name')->implode(', ');

        // Retrieve referral amount associated with the job
        $referalAmount = DB::table('job_service_cat')
            ->select('refferal_amount')
            ->where('job_id', $job_data->id)
            ->first();

        // Initialize extended hour amount and extended status
        $extendedHourAmount = 0;
        $workerTipAmount = 0; 
        $isExtended = $job->is_extended ? 'Yes' : 'No';

        // If the job is extended, calculate the extended hour amount
        if ($job->is_extended) {
            // Get the amount for the extended hours
            $extendedHourRate = DB::table('extended_hour')
                ->select('amount')
                ->first(); // Assuming amount is constant for all extended hours

            // Calculate extended hour amount
            if ($extendedHourRate) {
                $extendedHourAmount = $extendedHourRate->amount * $job->extended_hrs;
            }
        }

        $travelledAllowanceAmount = 0;
        $job->travelledAllowanceAmount = 0;
        $isTravelled = $job->is_travelled ? 'Yes' : 'No';
        $perKmAmount = 0;

        // If the job has travel allowance, calculate travelled allovance
        if ($job->is_travelled == 1) {
            //Get the amount for 1km travel allowance
            $travelledAllowanceRate = DB::table('transpotation_km_rate')
                ->select('amount')
                ->first();

            //Calculate travel allowance
            if ($travelledAllowanceRate) {
                $perKmAmount = $travelledAllowanceRate->amount;
               $travelledAllowanceAmount = $travelledAllowanceRate->amount * $job->travelled_km;
            }
        }

        if ($job->is_worker_tip == 1) {
            $workerTipAmount = $job->worker_tip_amount;
        }
        // Calculate grand total
        $grandTotal = ($referalAmount->refferal_amount ?? 0) + $extendedHourAmount + $workerTipAmount + $travelledAllowanceAmount;

        // Generate the PDF
        $pdf = Pdf::loadView('admin.invoice.invoice', [
            'job' => $job,
            'categoryNames' => $categoryNames,
            'referalAmount' => $referalAmount,
            'isExtended' => $isExtended,
            'extendedHourAmount' => $extendedHourAmount,
            'isTravelled' => $isTravelled,
            'travellAllowanceAmount' => $travelledAllowanceAmount,
            'perKmAmount' => $perKmAmount,
            'workerTipAmount' => $workerTipAmount,
            'grandTotal' => $grandTotal
        ]);

        $pdfPath = storage_path('app/invoice.pdf');
        $pdf->save($pdfPath);

        // Send email
        Mail::to($clientEmail)->send(new InvoiceMail($clientName, $message, $pdfPath));

        // Optionally, you can return a response or redirect
        //return redirect()->back()->with('success', 'Invoice sent successfully!');
    }

    public function extendJob(Request $request, $job_id)
    {
        $request->validate([
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

    public function addTravelledKM(Request $request, $job_id)
    {
        
        $validatedData = $request->validate([
            'km_travelled' => 'required', // Example validation rules
        ]);

        try {

            $job = Job::findOrFail($job_id);
            $job->is_travelled = 1;
            $job->travelled_km = $request->km_travelled;
            $job->save();

            //$refferal_user = User::findOrFail($job->user_id);

            // $data = ['device_token' => $refferal_user->fcm_token,
            //         'title' => 'Extended',
            //         'body' => 'Worker extended the job.'
            //         ];
            //$this->fcmservice->sendPushNotification($data);

            return response()->json(['status' => 200, 'success' => true, 'message' => 'Travel allowance added successfully'], 200);

        } catch (\Exception $e) {
            // Handle any exceptions (e.g., job not found)
            return response()->json(['status' => 500, 'success' => false, 'message' => 'Failed to update Travel status', 'error' => $e->getMessage()], 500);
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