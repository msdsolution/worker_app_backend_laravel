<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class VerificationController extends Controller
{
    public function verify(EmailVerificationRequest $request)
    {
    	dd("test");
    	// $user = User::findOrFail($id);

     //    if (! hash_equals($user->email_verification_hash, $hash)) {
     //        return Redirect::route('verification.notice')->withErrors(['message' => 'Invalid verification link']);
     //    }

     //    $user->markEmailAsVerified();
     //    return Redirect::route('home')->with('status', 'Email verified successfully');
    	
        // $request->fulfill();

        // // Optionally, add any additional logic or redirections here
        // return response()->json(['message' => 'Email verified successfully'], 200);
    }
}