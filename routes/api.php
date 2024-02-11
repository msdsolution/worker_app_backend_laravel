<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Mobile\JobApiController;

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

Route::get('/get_signup_form_data', [ApiController::class, 'getSignupFormData']);
Route::post('/register', [ApiController::class, 'register']);


Route::get('/check_holiday/{date}', [ApiController::class, 'checkDateStatus']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/user', [ApiController::class, 'user']);
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::post('/create_job', [JobApiController::class, 'createJob']);
	Route::get('/get_service_list', [JobApiController::class, 'getServiceList']);
	Route::get('/get_job_history_list', [JobApiController::class, 'getJobHistoryList']);
	Route::get('/get_job_form_data', [JobApiController::class, 'getJobCreatFormData']);
});
