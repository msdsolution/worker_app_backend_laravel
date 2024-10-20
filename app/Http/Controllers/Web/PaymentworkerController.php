<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use illuminate\Support\Facades\Storage;

class PaymentworkerController extends Controller
{
    public function index()
    {

        $payments = DB::table('worker_payment')
            ->leftJoin('worker_payment_attachment', 'worker_payment.id', '=', 'worker_payment_attachment.worker_payment_id')
            ->select(
                'worker_payment.id',
                'worker_payment.job_id',
                'worker_payment.amount',
                'worker_payment.status',
                'worker_payment_attachment.file_path'
            )
            ->get();

        return view('admin.paymentworker.index', compact('payments'));
    
    }
    public function create()
    {
        // Fetch workers with user_type 3
        $workers = User::where('user_type', 3)
                        ->select('id', 'first_name', 'last_name')
                        ->get();
        
        // Pass the workers to the view
        return view('admin.paymentworker.create', compact('workers'));
    }

    public function getWorkerJobs($workerId)
{    // Fetch job IDs that are already present in the worker_payment table
    $excludedJobIds = DB::table('worker_payment')
        ->select('job_id')
        ->pluck('job_id')
        ->toArray();

    // Fetch jobs based on worker_id and status, excluding those in the worker_payment table
    $jobs = DB::table('job')
        ->select('id', 'job_no')
        ->where('worker_id', $workerId)
        ->whereIn('status', [4, 5])
        ->whereNotIn('id', $excludedJobIds)
        ->get();

    // Return the fetched jobs as a JSON response
    return response()->json(['jobs' => $jobs]);
  
// $jobs = DB::table('job')
// ->select('id', 'job_no')
// ->where('worker_id', $workerId)
// ->where('status', 4)
// ->get();

// return response()->json(['jobs' => $jobs]);
}
//Ithu Worker amount Fucntion name apdiye vachitu fnction uluka change panitey

   /*  Method without adding the Tip amount */
    // $jobServiceCat = DB::table('job_service_cat')
    //     ->where('job_id', $jobId)
    //     ->first();

        

    // if ($jobServiceCat) {
    //     return response()->json(['worker_amount' => $jobServiceCat->worker_amount]);
    // } else {
    //     return response()->json(['worker_amount' => 0]);
    // }


   /* Ends here */
    /* Referal amount adding with tip without extended hr */
// public function getReferralAmount($jobId)
// {

//         // Retrieve the job details, including the status and tip information
//         $jobServiceCat = DB::table('job_service_cat')
//         ->where('job_id', $jobId)
//         ->first();

//     $jobDetails = DB::table('job')
//         ->where('id', $jobId)
//         ->select('status', 'is_worker_tip', 'worker_tip_amount')
//         ->first();

//     $workerAmount = $jobServiceCat ? $jobServiceCat->worker_amount : 0;

//     // If job status is 5 and is_worker_tip is 1, add the worker_tip_amount to the worker_amount
//     if ($jobDetails && $jobDetails->status == 5 && $jobDetails->is_worker_tip == 1) {
//         $workerAmount += $jobDetails->worker_tip_amount;
//     }
//     return response()->json(['worker_amount' => $workerAmount]);
// }

public function getReferralAmount($jobId)
{
    // Retrieve the job service category details, including worker amount
    $jobServiceCat = DB::table('job_service_cat')
        ->where('job_id', $jobId)
        ->first();

    // Retrieve job details, including status, tip, and extended hour information
    $jobDetails = DB::table('job')
        ->where('id', $jobId)
        ->select('status', 'is_worker_tip', 'worker_tip_amount', 'is_extended', 'extended_hrs')
        ->first();

    // Initialize worker amount (from job service category or 0 if not available)
    $workerAmount = $jobServiceCat ? $jobServiceCat->worker_amount : 0;

    // Add worker tip amount if job status is 5 and worker tip exists
    if ($jobDetails && $jobDetails->status == 5 && $jobDetails->is_worker_tip == 1) {
        $workerAmount += $jobDetails->worker_tip_amount;
    }

    // If the job is extended, calculate extended hour amount
    if ($jobDetails && $jobDetails->is_extended == 1) {
        // Retrieve the extended hour rate from the extended_hour table
        $extendedHourRate = DB::table('worker_extended_hr_rate')
            ->select('amount')
            ->first(); // Assuming a constant rate for extended hours
        
        // If an extended hour rate exists, multiply it by the extended hours
        if ($extendedHourRate) {
            $extendedHourAmount = $extendedHourRate->amount * $jobDetails->extended_hrs;
            // Add the extended hour amount to the worker amount
            $workerAmount += $extendedHourAmount;
        }
    }

    // Return the final worker amount, including any tip and extended hour calculations
    return response()->json(['worker_amount' => $workerAmount]);
}

public function store(Request $request)
{
   
    $validatedData = $request->validate([
        'jobs' => 'required|array',
        'jobs.*' => 'exists:job,id',
        'paid_amount' => 'required|numeric',
        'attachments.*' => '|file|mimes:pdf,jpeg,bmp,png,gif,svg', // Adjust file validation rules as needed
    ]);

    $jobs = $validatedData['jobs'];
    $paidAmount = $validatedData['paid_amount'];
    $status = 3; // Assuming status is always 3 for new payments

    
    try {
        // Insert into worker_payment table
        $workerPaymentIds = [];
        foreach ($jobs as $jobId) {
            $workerPaymentId = DB::table('worker_payment')->insertGetId([
                'job_id' => $jobId,
                'amount' => $paidAmount,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $workerPaymentIds[] = $workerPaymentId;

            // Handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    $filename = time() . '_' . $attachment->getClientOriginalName(); // Generate unique filename
                    $attachment->storeAs('worker_attachments', $filename); // Move file to public/worker_attachments with custom filename

                    // Insert into worker_payment_attachment table
                    DB::table('worker_payment_attachment')->insert([
                        'worker_payment_id' => $workerPaymentId,
                        'file_path' => $filename,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->back()->with('message', 'Payment added successfully.');
    } catch (\Exception $e) {
        // Log the exception for debugging purposes
        Log::error('Failed to add payment: ' . $e->getMessage());

        return redirect()->back()->withErrors(['error' => 'Failed to add payment. Please try again.']);
    }
}

public function download(Request $request,$filename)
{
  return response()->download(storage_path('app/worker_attachments/'.$filename));

}
public function view($filename)
{
    $path = storage_path('app/worker_attachments/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
}
}
