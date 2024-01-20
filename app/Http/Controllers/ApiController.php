<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Job_Service_Cat;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Token revoked successfully']);
    }

    public function register(Request $request)
    {
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
            'status' => 0, // Default status is set to 0
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function createJob(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'start_location' => 'required',
            'end_location' => 'required',
            'required_date' => 'required',
            'required_time' => 'required',
            'preferred_sex' => 'required',
            'job_categories' => 'required|array',
            'job_categories.*.name' => 'required',
            'job_categories.*.job_type_id' => 'required',
        ]);

        //dd($request->service_required_time);

        $job = Job::create($request->except('job_categories'));

        // Save job categories
        foreach ($request->input('job_categories') as $jobCategoryData) {
            Job_Service_Cat::create([
                'service_cat_id' => $jobCategoryData['job_type_id'],
                'job_id' => $job->id,
            ]);
        }

        return response()->json(['message' => 'Job created successfully', 'job' => $job], 201);
    }
}
