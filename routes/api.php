<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Mobile\JobApiController;
use App\Http\Controllers\Mobile\WorkerJobApiController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Auth::routes(['verify' => true]);

Route::post('/login', [ApiController::class, 'authenticate']);
Route::post('/refresh-token', [ApiController::class, 'refreshToken']);

Route::get('/get_signup_form_data', [ApiController::class, 'getSignupFormData']);
Route::post('/register', [ApiController::class, 'register']);
Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Email verified successfully'], 200);
})->name('verification.verify');
Route::post('email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification link sent!']);
})->middleware('auth:api')->name('verification.resend');


Route::get('/check_holiday/{date}', [ApiController::class, 'checkDateStatus']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/user', [ApiController::class, 'user']);
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::post('/create_job', [JobApiController::class, 'createJob']);
	Route::get('/get_service_list', [JobApiController::class, 'getServiceList']);
	Route::get('/get_job_history_list', [JobApiController::class, 'getJobHistoryList']);
	Route::get('/get_job_form_data', [JobApiController::class, 'getJobCreatFormData']);
	Route::get('/job_detail/{id}', [JobApiController::class, 'getJobDetail']);
	Route::post('/create_complaint', [JobApiController::class, 'submitJobComplaint']);
	Route::get('/getComplaints/{id}', [JobApiController::class, 'getAllJobComplaintsWithMessages']);

	Route::get('/get_worker_job_history_list', [WorkerJobApiController::class, 'getWorkerJobList']);
	Route::get('/getWorkerAcceptedAndStartedJobList', [WorkerJobApiController::class, 'getWorkerAcceptedAndStartedJobList']);
	Route::get('/getWorkerFinishedJobList', [WorkerJobApiController::class, 'getWorkerFinishedJobList']);
	Route::put('/job/{id}/accept', [WorkerJobApiController::class, 'acceptJob']);
	Route::put('/job/{id}/reject', [WorkerJobApiController::class, 'rejectJob']);
	Route::put('/job/{id}/start', [WorkerJobApiController::class, 'startJob']);
	Route::post('/job_finish', [WorkerJobApiController::class, 'finishJob']);
});
