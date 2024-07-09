<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\worker_feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkerFeedbackController extends Controller
{
    //
    public function index()
{
    $workerFeedback = DB::table('worker_feedback')
        ->join('job', 'worker_feedback.job_id', '=', 'job.id')
        ->join('users', 'worker_feedback.user_id', '=', 'users.id')
        ->select(
            'worker_feedback.id',
            'job.description as job_description',
            DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"),
            'worker_feedback.message',
            'worker_feedback.ratings',
            'worker_feedback.status' 
        )
        ->get();
    return view('admin.workerfeedback.index', compact('workerFeedback'));
}
public function changeStatus(Request $request) {

    $workerFeedback = worker_feedback::find($request->id);
    $workerFeedback->status = $request->status;
    $workerFeedback->save();
    return response()->json(['success' => 'Status Changed Successfully']);
}
}
