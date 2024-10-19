<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function index() {
        $InvDetails = DB::table('job')
        ->select(
            'job.id as jobId',
            'job_no',
            'job.user_id',
            'users.first_name as userFirstName',
            'users.last_name as userLastName', // Add this line
            'users.email as Email', // Add this line
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
            'job.updated_at',
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
        ->whereIn('job.status', [4,5])
        ->orderBy('job.created_at', 'desc')
        ->get();

        foreach ($InvDetails as $job) {
            if ($job->status == 4) {
                // Check if the updated_at date is within the last 7 days
                $updatedAt = Carbon::parse($job->updated_at);
                $job->isOverdue = $updatedAt->lt(Carbon::now()->subDays(7)); // True if it's overdue
            }
        }

        return view('admin.invoice.index', compact('InvDetails'));
    }
    
    public function download($jobId) {
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
            'job.worker_tip_amount'
        )
        ->leftJoin('users', 'job.user_id', '=', 'users.id')
        ->leftJoin('users as workers', 'job.worker_id', '=', 'workers.id')
        ->leftJoin('cities', 'job.city_id', '=', 'cities.id') 
        ->where('job.id', $jobId)
        ->first();

        // Retrieve all service categories associated with the job
        $serviceCategories = DB::table('job_service_cat')
            ->join('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
            ->select('service_cat.name')
            ->where('job_service_cat.job_id', $jobId)
            ->get();

        // Concatenate service category names into a single string
        $categoryNames = $serviceCategories->pluck('name')->implode(', ');

        // Retrieve one referal amount associated with the job
        $referalAmount = DB::table('job_service_cat')
            ->select('refferal_amount')
            ->where('job_id', $jobId)
            ->first();
            $extendedHourAmount = 0;
            $workerTipAmount = 0; 

        $isExtended = $job->is_extended ? 'Yes' : 'No';

        // If the job is extended, calculate the extended hour amount
        if ($job->is_extended) {
            // Get the amount for the extended hours
            $extendedHourRate = DB::table('refferal_extended_hr_rate')
                ->select('amount')
                ->first(); // Assuming amount is constant for all extended hours
            
            // Calculate extended hour amount
            if ($extendedHourRate) {
                $extendedHourAmount = $extendedHourRate->amount * $job->extended_hrs;
            }
        }


        if ($job->is_worker_tip == 1) {
            $workerTipAmount = $job->worker_tip_amount;
        }

        // Calculate grand total
        $grandTotal = ($referalAmount->refferal_amount ?? 0) + $extendedHourAmount +  $workerTipAmount;

        // Pass data to the view and generate the PDF
        $pdf = Pdf::loadView('admin.invoice.invoice', [
            'job' => $job,
            'categoryNames' => $categoryNames,
            'referalAmount' => $referalAmount,
            'isExtended' => $isExtended,
            'extendedHourAmount' => $extendedHourAmount,
            'workerTipAmount' => $workerTipAmount,
            'grandTotal' => $grandTotal
        ]);
        return $pdf->download('invoice.pdf');
    }

    public function view($jobId) {
        // Retrieve job details
        // $job = DB::table('job')
        //     ->select(
        //         'job.id as jobId',
        //         'job_no',
        //         'job.user_id',
        //         'users.first_name as userFirstName',
        //         'users.last_name as userLastName',
        //         'users.email as Email',
        //         'users.user_address as Address',
        //         'users.phone_no as Phonenumber',
        //         'job.description as jobDescription',
        //         'job.city_id',
        //         'cities.name_en as cityName',
        //         'job.start_location',
        //         'job.end_location',
        //         'job.worker_id',
        //         'workers.first_name as workerName',
        //         'job.status',
        //         'job.required_date',
        //         'job.required_time',
        //         'job.created_at',
        //         'job.preferred_sex'
        //     )
        //     ->leftJoin('users', 'job.user_id', '=', 'users.id')
        //     ->leftJoin('users as workers', 'job.worker_id', '=', 'workers.id')
        //     ->leftJoin('cities', 'job.city_id', '=', 'cities.id') 
        //     ->where('job.id', $jobId)
        //     ->first();

        // // Retrieve all service categories associated with the job
        // $serviceCategories = DB::table('job_service_cat')
        //     ->join('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
        //     ->select('service_cat.name')
        //     ->where('job_service_cat.job_id', $jobId)
        //     ->get();

        // // Concatenate service category names into a single string
        // $categoryNames = $serviceCategories->pluck('name')->implode(', ');

        // // Retrieve one referal amount associated with the job
        // $referalAmount = DB::table('job_service_cat')
        //     ->select('refferal_amount')
        //     ->where('job_id', $jobId)
        //     ->first();

        // // Generate the PDF view
        // $pdf = Pdf::loadView('admin.invoice.invoice', [
        //     'job' => $job,
        //     'categoryNames' => $categoryNames,
        //     'referalAmount' => $referalAmount
        // ]);

        // // Return the PDF as a stream to be viewed in the browser
        // return $pdf->stream('invoice.pdf');
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
                'job.worker_tip_amount'
        )
        ->leftJoin('users', 'job.user_id', '=', 'users.id')
        ->leftJoin('users as workers', 'job.worker_id', '=', 'workers.id')
        ->leftJoin('cities', 'job.city_id', '=', 'cities.id') 
        ->where('job.id', $jobId)
        ->first();

        // Retrieve all service categories associated with the job
        $serviceCategories = DB::table('job_service_cat')
            ->join('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
            ->select('service_cat.name')
            ->where('job_service_cat.job_id', $jobId)
            ->get();

        // Concatenate service category names into a single string
        $categoryNames = $serviceCategories->pluck('name')->implode(', ');

        // Retrieve referral amount associated with the job
        $referalAmount = DB::table('job_service_cat')
            ->select('refferal_amount')
            ->where('job_id', $jobId)
            ->first();

        // Initialize extended hour amount and extended status
        $extendedHourAmount = 0;
        $workerTipAmount = 0; 

        $isExtended = $job->is_extended ? 'Yes' : 'No';

        // If the job is extended, calculate the extended hour amount
        if ($job->is_extended) {
            // Get the amount for the extended hours
            $extendedHourRate = DB::table('refferal_extended_hr_rate')
                ->select('amount')
                ->first(); // Assuming amount is constant for all extended hours
            
            // Calculate extended hour amount
            if ($extendedHourRate) {
                $extendedHourAmount = $extendedHourRate->amount * $job->extended_hrs;
            }
        }


        if ($job->is_worker_tip == 1) {
            $workerTipAmount = $job->worker_tip_amount;
        }
        // Calculate grand total
        $grandTotal = ($referalAmount->refferal_amount ?? 0) + $extendedHourAmount +   $workerTipAmount ;

        // Generate the PDF view
        $pdf = Pdf::loadView('admin.invoice.invoice', [
            'job' => $job,
            'categoryNames' => $categoryNames,
            'referalAmount' => $referalAmount,
            'isExtended' => $isExtended,
            'extendedHourAmount' => $extendedHourAmount,
            'workerTipAmount' => $workerTipAmount,
            'grandTotal' => $grandTotal
        ]);

        // Return the PDF as a stream to be viewed in the browser
        return $pdf->stream('invoice.pdf');
    }

    public function sendInvoice(Request $request)
    {
        $jobId = $request->input('jobId');
        $clientName = $request->input('clientName');
        $clientEmail = $request->input('clientEmail');
        $message = $request->input('message');

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
                'job.worker_tip_amount'
            )
            ->leftJoin('users', 'job.user_id', '=', 'users.id')
            ->leftJoin('users as workers', 'job.worker_id', '=', 'workers.id')
            ->leftJoin('cities', 'job.city_id', '=', 'cities.id')
            ->where('job.id', $jobId)
            ->first();

        // Retrieve all service categories associated with the job
        $serviceCategories = DB::table('job_service_cat')
            ->join('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
            ->select('service_cat.name')
            ->where('job_service_cat.job_id', $jobId)
            ->get();

        // Concatenate service category names into a single string
        $categoryNames = $serviceCategories->pluck('name')->implode(', ');

        // Retrieve referral amount associated with the job
        $referalAmount = DB::table('job_service_cat')
            ->select('refferal_amount')
            ->where('job_id', $jobId)
            ->first();

        // Initialize extended hour amount and extended status
        $extendedHourAmount = 0;
        $workerTipAmount = 0; 
        $isExtended = $job->is_extended ? 'Yes' : 'No';

        // If the job is extended, calculate the extended hour amount
        if ($job->is_extended) {
            // Get the amount for the extended hours
            $extendedHourRate = DB::table('refferal_extended_hr_rate')
                ->select('amount')
                ->first(); // Assuming amount is constant for all extended hours

            // Calculate extended hour amount
            if ($extendedHourRate) {
                $extendedHourAmount = $extendedHourRate->amount * $job->extended_hrs;
            }
        }

        if ($job->is_worker_tip == 1) {
            $workerTipAmount = $job->worker_tip_amount;
        }
        // Calculate grand total
        $grandTotal = ($referalAmount->refferal_amount ?? 0) + $extendedHourAmount + $workerTipAmount;

        // Generate the PDF
        $pdf = Pdf::loadView('admin.invoice.invoice', [
            'job' => $job,
            'categoryNames' => $categoryNames,
            'referalAmount' => $referalAmount,
            'isExtended' => $isExtended,
            'extendedHourAmount' => $extendedHourAmount,
            'workerTipAmount' => $workerTipAmount,
            'grandTotal' => $grandTotal
        ]);

        $pdfPath = storage_path('app/invoice.pdf');
        $pdf->save($pdfPath);

        // Send email
        Mail::to($clientEmail)->send(new InvoiceMail($clientName, $message, $pdfPath));

        // Optionally, you can return a response or redirect
        return redirect()->back()->with('success', 'Invoice sent successfully!');
    }
}
