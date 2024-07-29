<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    //
    public function index()
    {
        // $jobsWithComplaints = DB::table('job')
        // ->where('is_complaint', 1)
        // ->pluck('job_no'); // Retrieve job numbers only
        $jobsWithComplaints = DB::table('job')
        ->where('is_complaint', 1)
        ->get(['id', 'job_no', 'complaint_status']); // Fetch objects with both 'id' and 'job_no'

    return view('admin.complaint.index', ['jobs' => $jobsWithComplaints]);

  //  return view('admin.complaint.index', ['jobs' => $jobsWithComplaints]);
    }
    public function getChatMessages($jobId)
    {
        $messages = DB::table('complaint_messages')
            ->leftJoin('users', 'complaint_messages.user_id', '=', 'users.id')
            ->leftJoin('complaint_attachments', 'complaint_messages.id', '=', 'complaint_attachments.complaint_message_id')
            ->where('complaint_messages.job_id', $jobId)
            ->select(
                'complaint_messages.user_id',
                'users.first_name',
                'complaint_messages.message',
                'complaint_attachments.img_url'
            )
            ->orderBy('complaint_messages.created_at')
            ->get();
    
        foreach ($messages as $message) {
            if ($message->img_url) {
                $message->img_url = url('storage/' . $message->img_url);
            }
        }
    
        // Retrieve the complaint_status for the job
        $job = DB::table('job')->where('id', $jobId)->select('complaint_status')->first();
        $complaintStatus = $job->complaint_status;
    
        return view('admin.complaint.chat', [
            'messages' => $messages,
            'jobId' => $jobId,
            'complaintStatus' => $complaintStatus // Pass the complaint status to the view
        ]);
    }
    
    public function sendMessage(Request $request)
    {
        $request->validate([
            'job_id' => 'required|integer',
            'user_id' => 'required|integer',
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048', // Validate files
        ]);
    
        // Insert the new message and get its ID
        $messageId = DB::table('complaint_messages')->insertGetId([
            'job_id' => $request->input('job_id'),
            'user_id' => $request->input('user_id'),
            'message' => $request->input('message'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            foreach ($attachments as $attachment) {
                // Generate a unique file name
                // Store the file in storage/app/complaint_attachments
                $filePath = $attachment->store('complaintAttachment', 'public');
                // Save attachment info in the database
                DB::table('complaint_attachments')->insert([
                    'complaint_message_id' => $messageId,
                    'job_id' => $request->input('job_id'),
                    'img_url' => $filePath, // Path relative to storage/app
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    
        // Check if the user is the admin (user_id = 1)
        if ($request->input('user_id') == 1) {
            // Update the job's complaint status to 1
            DB::table('job')
                ->where('id', $request->input('job_id'))
                ->update(['complaint_status' => 1]);
        }
    
        // Redirect back to the chat view with a success message
        return redirect()->back()->with('status', 'Message sent!');
    }
    public function updateComplaintStatus(Request $request)
    {
        $jobId = $request->input('job_id');
        $status = $request->input('complaint_status');
    
        DB::table('job')
            ->where('id', $jobId)
            ->update(['complaint_status' => $status]);
    
        return response()->json(['success' => 'Status updated successfully']);
    }

    

}
