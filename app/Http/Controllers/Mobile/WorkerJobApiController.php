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
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WorkerJobApiController extends Controller
{

	public function getWorkerJobList(Request $request)
    {
        
       $user = auth()->user();

       $job_history = Job::where('worker_id', $user->id)
       				  ->where('status', 0)
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
        $job->status = 0;
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

    public function finishJob($id)
    {
        $job = Job::findOrFail($id);
        $job->status = 4;
        $job->save();

        return response()->json([
        	'status' => 200,
	        'success' => true,
        	'message' => 'Job finished successfully',
        ], 200);
    }

    // public function extendJob($id)
    // {
    //     $job = Job::findOrFail($id);
    //     $job->status = 'extended';
    //     $job->save();

    //     return response()->json(['message' => 'Job extended successfully']);
    // }
}