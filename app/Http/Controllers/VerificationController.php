<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;


class VerificationController extends Controller
{
    public function verify(EmailVerificationRequest $request)
    {
    
    	// Retrieve the user by ID
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        //dd($request->hash);

        // Check the verification hash
        if (! sha1($user->getEmailForVerification()) === $request->hash) {
            return response()->json(['success' => false, 'message' => 'Invalid verification link.'], 400);
        }

        

        // Check if the email is already verified
        if ($user->hasVerifiedEmail()) {
            return response()->json(['success' => false, 'message' => 'Email already verified.'], 400);
        }

        // Mark the email as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            return response()->json(['success' => true, 'message' => 'Email verified successfully!'], 200);
        }

        return response()->json(['success' => false, 'message' => 'Email could not be verified.'], 500);
    }
}