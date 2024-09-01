<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RefferalPayment;
use Illuminate\Http\Request;

class PaymentRefferalController extends Controller
{
    //
    public function Index()
    {
        $refferalpayment = RefferalPayment::all();
        return view('admin.paymentrefferal.index',compact('refferalpayment'));

    }
}
