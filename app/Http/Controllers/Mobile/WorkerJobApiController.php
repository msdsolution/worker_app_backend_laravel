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
use App\Models\JobAttachment;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WorkerJobApiController extends Controller
{

	public function getWorkerJobList(Request $request)
    {
        
       $user = auth()->user();

       $job_statuses = [1];

       $job_history = Job::where('worker_id', $user->id)
       				  ->whereIn('status', $job_statuses)
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

       $job_history = Job::where('worker_id', $user->id)
                      ->where('status', 4)
                      ->get();

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

        return response()->json([
        	'status' => 200,
	        'success' => true,
        	'message' => 'Job rejected successfully',
        ], 200);
    }

    public function startJob($id)
    {
        $job = Job::findOrFail($id);
        $job->status = 3;
        $job->save();

        return response()->json([
        	'status' => 200,
	        'success' => true,
        	'message' => 'Job started successfully',
        ], 200);
    }

    public function finishJob(Request $request)
    {

        $request->validate([
            'job_id' => 'required',
            'finish_job_description',
            //'files.*' => 'file|mimes:jpeg,png,gif|max:20000',
        ]);

        $job = Job::findOrFail($request->job_id);
        $job->finishJobDescription = $request->finish_job_description;
        $job->status = 4;
        $job->save();

        //Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Ensure the directory exists or create it
                $directory = 'jobAttachment';
                if (!Storage::exists($directory)) {
                    Storage::makeDirectory($directory);
                }

                // Store the file in the specified directory
                $path = $file->store($directory);

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

            return response()->json(['status' => 200, 'success' => true, 'message' => 'Job extended successfully']);

        } catch (\Exception $e) {
            // Handle any exceptions (e.g., job not found)
            return response()->json(['status' => 500, 'success' => false, 'message' => 'Failed to update extended status', 'error' => $e->getMessage()]);
        }
    }
}