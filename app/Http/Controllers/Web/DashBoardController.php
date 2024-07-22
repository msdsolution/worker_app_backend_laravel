<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    public function index()
    {  
      $clientsCount = User::where('user_type', 2)->count();
      // $Assignedworkcount = Job::where('status', 1)->count();
      $Pendingworkcount = Job::where('status', 0)->count();
      $Rejectedworkcount = Job::where('status', 6)->count();

      $currentMonth = now()->month;
      $currentYear = now()->year;
      
      // Query to count jobs created in the current month
      $jobCount = Job::whereYear('created_at', $currentYear)
                     ->whereMonth('created_at', $currentMonth)
                     ->count();
     $Assignedworkcount= Job::where('status', 1) // Status 2 for assigned jobs
     ->whereYear('created_at', $currentYear)
     ->whereMonth('created_at', $currentMonth)
     ->count();

     $Completedworkcount= Job::where('status', 4) // Status 2 for assigned jobs
     ->whereYear('created_at', $currentYear)
     ->whereMonth('created_at', $currentMonth)
     ->count();
     $CompletedworkPaidcount= Job::where('status', 5) // Status 2 for assigned jobs
     ->whereYear('created_at', $currentYear)
     ->whereMonth('created_at', $currentMonth)
     ->count();

      return view('admin.dashboard', compact('jobCount','clientsCount','Assignedworkcount','Pendingworkcount','Rejectedworkcount','Completedworkcount','CompletedworkPaidcount'));
    }
}
