<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BankTransferPaymetRefferal;
use App\Models\Job;
use Illuminate\Http\Request;

class BankTransferController extends Controller
{
    
public function index()
{
    $Banktransfer = BankTransferPaymetRefferal::all();
    return view('admin.banktransfer.index',compact('Banktransfer'));
}

public function changeStatusBankTransfer(Request $request) {

    // $Banktransfer = BankTransferPaymetRefferal::find($request ->id);
    // $Banktransfer->status = $request->status;
    // $Banktransfer->save();
    // return response()->json(['success' => 'Status Changed Successfully']);
    $bankTransfer = BankTransferPaymetRefferal::find($request->id);

    // Update the bank transfer status
    $bankTransfer->status = $request->status;
    $bankTransfer->save();

    // Update the job status based on the bank transfer status
    $job = Job::where('id', $bankTransfer->job_id)->first(); // Get the associated job
    if ($job) {
        if ($request->status == 1) { // Approved
            $job->status = 5; // Set job status to 5 (Approved)
        } else { // Unapproved
            $job->status = 8; // Set job status to 8 (Unapproved)
        }
        $job->save(); // Save the job status change
    }

    return response()->json(['success' => 'Status Changed Successfully']);
}


}
