<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\DashBoardController;



Route::get('/', function () {
    return view('welcome');
});

// Route::get('/', function () {
//     return view('/login');
// })->middleware('auth','isAdmin');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::prefix('admin')->group(function(){

Route::get('/dashboard',[DashBoardController::class,'index']);
Route::get('client',[ClientController::class,'index']);
Route::get('add-client',[ClientController::class,'create']);
Route::post('add-client',[ClientController::class,'store']);
    

Route::get('employees',[EmployeeController::class,'index']);
Route::get('add-employee',[EmployeeController::class,'create']);
Route::post('add-employee',[EmployeeController::class,'store']);
});
