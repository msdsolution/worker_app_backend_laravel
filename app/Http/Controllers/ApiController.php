<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Job_Service_Cat;
use App\Models\Holiday;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use App\Notifications\VerifyEmail;

class ApiController extends Controller
{
    public function testApi()
    {
        // Make a GET request to a sample API (replace with your API endpoint)
        $response = Http::get('https://jsonplaceholder.typicode.com/posts/1');

        // Decode the JSON response
        $data = $response->json();

        // Display the response
        return response()->json($data);
    }

    public function register(Request $request)
    {
        if (User::where('email', $request['email'])->exists()) {
            return  response()->json(['success' => false,'message' => 'The email address is already registered.']);
        }

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'location' => 'required',
            'password' => 'required|min:6',
            'user_type' => 'required',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'location' => $request->input('location'),
            'password' => Hash::make($request->input('password')),
            'user_type' => $request->input('user_type'),
            'status' => $request->input('user_type') == 2 ? 1 : 0, // Default status is set to 0
        ]);

        // Send email verification link
        //$user->notify(new VerifyEmail);

        return response()->json(['success' => true,'message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                 'success' => false,
                 'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
     return $credentials;
            return response()->json([
                 'success' => false,
                 'message' => 'Could not create token.',
                ], 500);
        }
  
        if (auth()->user()->status == 1) {
            //Token created, return with success response and jwt token
            return response()->json([
                'success' => true,
                'token' => $token,
                'data' => auth()->user(),
             ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "User is not verified",
             ]);
        }
   
    }

    public function user()
    {
        return response()->json(Auth::user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }


    

    public function checkDateStatus($selectedDate)
	{
	    //$selectedDate = Carbon::parse($selectedDate);
	    $carbonInstance = Carbon::createFromFormat('M d, Y', $selectedDate);
	    $exists = Holiday::where('date', $carbonInstance->format('M d, Y'))->exists();

	    //dd($carbonInstance->format('M d, Y'));

	    // Check holiday
	    if ($exists) {
	        return response()->json(['message' => 'success', 'data' => 'holiday'], 201);
	    }

	    // Check weekend (Saturday or Sunday)
	    if ($carbonInstance->isWeekend()) {
	        return response()->json(['message' => 'success', 'data' => 'weekend'], 201);
	    }

	    // weekday
	    return response()->json(['message' => 'success', 'data' => 'weekday'], 201);
	}
}
