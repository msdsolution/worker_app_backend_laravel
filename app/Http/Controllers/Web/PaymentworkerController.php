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
        ->where('status', 4)
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
public function getReferralAmount($jobId)
{
    $jobServiceCat = DB::table('job_service_cat')
        ->where('job_id', $jobId)
        ->first();

    if ($jobServiceCat) {
        return response()->json(['referral_amount' => $jobServiceCat->refferal_amount]);
    } else {
        return response()->json(['referral_amount' => 0]);
    }
}
public function store(Request $request)
{
   
    $validatedData = $request->validate([
        'jobs' => 'required|array',
        'jobs.*' => 'exists:job,id',
        'paid_amount' => 'required|numeric',
        'attachments.*' => 'file|mimes:pdf,jpeg,bmp,png,gif,svg', // Adjust file validation rules as needed
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
