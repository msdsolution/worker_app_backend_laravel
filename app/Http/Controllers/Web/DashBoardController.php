<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    public function index()
    {   //return 'i am dashbaord';
      return view('admin.dashboard');
    }
}
