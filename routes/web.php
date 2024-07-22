<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\ClientRateController;
use App\Http\Controllers\Web\DashBoardController;
use App\Http\Controllers\Web\extendedhourController;
use App\Http\Controllers\Web\InvoiceController;
use App\Http\Controllers\Web\JobListingController;
use App\Http\Controllers\Web\PaymentworkerController;
use App\Http\Controllers\Web\ServicecategoryController;
use App\Http\Controllers\Web\WorkerFeedbackController;
use App\Http\Controllers\Web\workerRateController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('/login');
})->middleware('auth','isAdmin');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::prefix('admin')->group(function(){

Route::get('/dashboard',[DashBoardController::class,'index']);
Route::get('client',[ClientController::class,'index']);
Route::get('add-client',[ClientController::class,'create']);
Route::post('add-client',[ClientController::class,'store']);

Route::get('client/{client_id}',[ClientController::class,'edit']);
Route::put('update-client/{client_id}',[ClientController::class,'update']);

Route::get('delete-client/{client_id}',[ClientController::class,'destroy']);
    

Route::get('employees',[EmployeeController::class,'index']);
Route::get('add-employee',[EmployeeController::class,'create']);
Route::post('add-employee',[EmployeeController::class,'store']);

Route::get('edit-employee/{employee_id}',[EmployeeController::class,'edit']);
Route::put('update-employee/{employee_id}',[EmployeeController::class,'update']);

Route::get('delete-employee/{employee_id}',[EmployeeController::class,'destroy']);

Route::get('changeStatusemp', [EmployeeController::class, 'changeStatusemp'])->name('changeStatusemp');


Route::get('servicecategory',[ServicecategoryController::class,'index']);

Route::get('add-service',[ServicecategoryController::class,'create']);
Route::post('add-service',[ServicecategoryController::class,'store']);

 Route::get('edit-service/{Service_Category_id}',[ServicecategoryController::class,'edit']);
 Route::put('update-service/{Service_Category_id}',[ServicecategoryController::class,'update']);

 Route::get('delete-service/{Service_Category_id}',[ServicecategoryController::class,'destroy']);


 Route::get('worker_rate',[workerRateController::class,'index']);
 Route::get('add-worker_rate',[workerRateController::class,'create']);
Route::post('add-worker_rate',[workerRateController::class,'store']);

Route::get('edit-workerrate/{workerrate_id}',[workerRateController::class,'edit']);
Route::put('update-workerrate/{workerrate_id}',[workerRateController::class,'update']);
Route::get('delete-workerrate/{workerrate_id}',[workerRateController::class,'destroy']);



Route::get('client_rate',[ClientRateController::class,'index']);
Route::get('add-client_rate',[ClientRateController::class,'create']);
Route::post('add-client_rate',[ClientRateController::class,'store']);

Route::get('edit-clientrate/{clientrate_id}',[ClientRateController::class,'edit']);
Route::put('update-clientrate/{clientrate_id}',[ClientRateController::class,'update']);
Route::get('delete-clientrate/{clientrate_id}',[ClientRateController::class,'destroy']);


// Route::get('joblisting',[JobListingController::class,'index']);
//Route::get('joblisting', [JobListingController::class, 'showDetails']);
Route::get('joblisting', [JobListingController::class, 'index']);

Route::get('assign-job/{jobId}', [JobListingController::class, 'assign'])->name('assign-job');
//Route::put('job.update/{jobId}', [JobListingController::class, 'update'])->name('job.update');
Route::put('assigning-job/{jobId}', [JobListingController::class, 'update'])->name('assigning-job');



// Route::get('assigning-job/{jobId}', [JobListingController::class, 'Assigning']);

Route::get('workerfeedback', [WorkerFeedbackController::class, 'index']);


Route::get('changeStatus', [WorkerFeedbackController::class, 'changeStatus'])->name('changeStatus');

Route::get('payment_worker',[PaymentworkerController::class,'index']);
Route::get('add-paymentworker',[PaymentworkerController::class,'create']);
Route::post('add-paymentworker',[PaymentworkerController::class,'store']);
Route::get('get-worker-jobs/{workerId}', 'App\Http\Controllers\Web\PaymentworkerController@getWorkerJobs');
Route::get('get-referral-amount/{jobId}', [PaymentworkerController::class, 'getReferralAmount']);
Route::post('store-payment', [PaymentworkerController::class, 'store'])->name('store-payment');
Route::get('download/{filename}', [PaymentworkerController::class, 'download']);
Route::get('view/{filename}', [PaymentworkerController::class, 'view'])->name('view');

Route::get('Invoice', [InvoiceController::class, 'index']);
Route::get('download-pdf/{jobId}', [InvoiceController::class, 'download'])->name('download-pdf');
Route::get('view-pdf/{jobId}', [InvoiceController::class, 'view'])->name('view-pdf');

Route::get('extended-hour', [extendedhourController::class, 'index']);
Route::get('add-extdhour', [extendedhourController::class, 'create']);
Route::post('add-extdhour',[extendedhourController::class,'store']);
// Route::get('/joblisting/{jobServiceCat}', 'Web\JobListingController@index')->name('joblisting.index');



});
