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
            return $this->emailVerifyView(2);
            //return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        //dd($request->hash);

        // Check the verification hash
        if (! sha1($user->getEmailForVerification()) === $request->hash) {
            return $this->emailVerifyView(2);
            //return response()->json(['success' => false, 'message' => 'Invalid verification link.'], 400);
        }

        

        // Check if the email is already verified
        if ($user->hasVerifiedEmail()) {
            return $this->emailVerifyView(3);
            //return response()->json(['success' => false, 'message' => 'Email already verified.'], 400);
        }

        // Mark the email as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            return $this->emailVerifyView(1);
            //return response()->json(['success' => true, 'message' => 'Email verified successfully!'], 200);
        }
        return $this->emailVerifyView(2);
        //return response()->json(['success' => false, 'message' => 'Email could not be verified.'], 500);
    }

    public function resend(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && !$user->hasVerifiedEmail()) {
            // Send the verification email again
            $user->sendEmailVerificationNotification();

            return response()->json(['status' => true, 'message' => 'Verification link sent!'], 200);
        }

        return response()->json(['status' => true, 'message' => 'This email is not registered or is already verified.']);
    }

    public function emailVerifyView($status){
        if ($status == 1) {
            return $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Email Verified</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        background-color: #f0f0f0;
                    }
                    .dialog {
                        display: none;
                        background: white;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        padding: 20px;
                        width: 300px;
                        text-align: center;
                    }
                    .dialog.show {
                        display: block;
                    }
                    .dialog h2 {
                        color: #4CAF50;
                    }
                    .dialog p {
                        margin: 15px 0;
                    }
                    .dialog button {
                        background-color: #4CAF50;
                        border: none;
                        color: white;
                        padding: 10px 20px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;
                        margin: 10px 2px;
                        cursor: pointer;
                        border-radius: 5px;
                    }
                </style>
            </head>
            <body>

                <div class="dialog" id="paymentDialog">
                    <h2>Email Verified Success</h2>
                    <p>Your email has been verified successfully!</p>
                    <p>Please close the browser and login to Ratamithuro mobile app</p>
                    <button onclick="closeDialog()">Close</button>
                </div>

                <script>
                    // Function to show the dialog
                    function showDialog() {
                        document.getElementById("paymentDialog").classList.add("show");
                    }

                    // Function to close the dialog
                    function closeDialog() {
                        document.getElementById("paymentDialog").classList.remove("show");
                    }

                    // Automatically show the dialog after 1 second (for demonstration purposes)
                    setTimeout(showDialog, 1000);
                </script>

            </body>
            </html>';
        } else if ($status == 2) {
            return $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Failed</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        background-color: #f0f0f0;
                    }
                    .dialog {
                        display: none;
                        background: white;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        padding: 20px;
                        width: 300px;
                        text-align: center;
                    }
                    .dialog.show {
                        display: block;
                    }
                    .dialog h2 {
                        color: #FF0000;
                    }
                    .dialog p {
                        margin: 15px 0;
                    }
                    .dialog button {
                        background-color: #FF0000;
                        border: none;
                        color: white;
                        padding: 10px 20px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;
                        margin: 10px 2px;
                        cursor: pointer;
                        border-radius: 5px;
                    }
                </style>
            </head>
            <body>

                <div class="dialog" id="paymentDialog">
                    <h2>Email could not be verified</h2>
                    <p>Please Contact Admin</p>
                    <button onclick="closeDialog()">Close</button>
                </div>

                <script>
                    // Function to show the dialog
                    function showDialog() {
                        document.getElementById("paymentDialog").classList.add("show");
                    }

                    // Function to close the dialog
                    function closeDialog() {
                        document.getElementById("paymentDialog").classList.remove("show");
                    }

                    // Automatically show the dialog after 1 second (for demonstration purposes)
                    setTimeout(showDialog, 1000);
                </script>

            </body>
            </html>';
        } else if ($status == 3) {
            return $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Already Verified</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        background-color: #f0f0f0;
                    }
                    .dialog {
                        display: none;
                        background: white;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        padding: 20px;
                        width: 300px;
                        text-align: center;
                    }
                    .dialog.show {
                        display: block;
                    }
                    .dialog h2 {
                        color: #4CAF50;
                    }
                    .dialog p {
                        margin: 15px 0;
                    }
                    .dialog button {
                        background-color: #4CAF50;
                        border: none;
                        color: white;
                        padding: 10px 20px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;
                        margin: 10px 2px;
                        cursor: pointer;
                        border-radius: 5px;
                    }
                </style>
            </head>
            <body>

                <div class="dialog" id="paymentDialog">
                    <h2>Already email verified</h2>
                    <p>Please login to the RataMithuro app</p>
                    <button onclick="closeDialog()">Close</button>
                </div>

                <script>
                    // Function to show the dialog
                    function showDialog() {
                        document.getElementById("paymentDialog").classList.add("show");
                    }

                    // Function to close the dialog
                    function closeDialog() {
                        document.getElementById("paymentDialog").classList.remove("show");
                    }

                    // Automatically show the dialog after 1 second (for demonstration purposes)
                    setTimeout(showDialog, 1000);
                </script>

            </body>
            </html>';
        }
        
    }

    public function resend(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && !$user->hasVerifiedEmail()) {
            // Send the verification email again
            $user->sendEmailVerificationNotification();

            return response()->json(['status' => true, 'message' => 'Verification link sent!'], 200);
        }

        return response()->json(['status' => true, 'message' => 'This email is not registered or is already verified.']);
    }

    public function resend(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && !$user->hasVerifiedEmail()) {
            // Send the verification email again
            $user->sendEmailVerificationNotification();

            return response()->json(['status' => true, 'message' => 'Verification link sent!'], 200);
        }

        return response()->json(['status' => true, 'message' => 'This email is not registered or is already verified.']);
    }
}