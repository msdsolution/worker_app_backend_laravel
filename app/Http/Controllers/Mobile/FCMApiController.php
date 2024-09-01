<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Controllers\Controller;


class FCMApiController extends Controller
{

	 protected $firebase;

    public function __construct()
    {
        // Path to the Firebase JSON file in the public directory
        $firebaseJsonPath = env('FIREBASE_CREDENTIALS_PATH');

        // Initialize Firebase
        $this->firebase = (new Factory)
            ->withServiceAccount($firebaseJsonPath)
            ->create();

        $this->messaging = $this->firebase->getMessaging();
    }

    public function updateDeviceToken(Request $request)
    {

    	$userId = Auth::id();

        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        // Find the user by ID
	    $user = User::find($userId);

	    // Check if user exists
	    if (!$user) {
	        return response()->json(['status' => 404, 'success' => true, 'message' => 'User not found'], 404);
	    }

	    // Update the user's FCM token
	    $user->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['status' => 200, 'success' => true, 'message' => 'Device token updated successfully']);
    }

	public function sendPushNotification($data){

	    // $credentialsFilePath = env('FIREBASE_CREDENTIALS_PATH');
	    // $client = new \Google_Client();
	    // $client->setAuthConfig($credentialsFilePath);
	    // $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
	    // $apiurl = 'https://fcm.googleapis.com/v1/projects/ratamithuro-e9039/messages:send';
	    // $client->refreshTokenWithAssertion();
	    // $token = $client->getAccessToken();
	    // $access_token = $token['access_token'];
	    
	    // $headers = [
	    //      "Authorization: Bearer $access_token",
	    //      'Content-Type: application/json'
	    // ];
	    // $test_data = [
	    //     "title" => "TITLE_HERE",
	    //     "description" => "DESCRIPTION_HERE",
	    // ]; 
	    
	    // $data['data'] =  $test_data;

	    // $data['token'] = $user['fcm_token']; // Retrive fcm_token from users table

	    // $payload['message'] = $data;
	    // $payload = json_encode($payload);
	    // $ch = curl_init();
	    // curl_setopt($ch, CURLOPT_URL, $apiurl);
	    // curl_setopt($ch, CURLOPT_POST, true);
	    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	    // curl_exec($ch);
	    // $res = curl_close($ch);
	    // if($res){
	    //     return response()->json([
	    //                   'message' => 'Notification has been Sent'
	    //            ]);
	    // }

	    if (!$data->device_token) {
        	return response()->json(['message' => 'User does not have a device token'], 400);
        }

	    // Example payload for sending a notification
        $message = CloudMessage::withTarget('token', $data->device_token)
            ->withNotification(Notification::create($data->title, $data->body));


        try {
            $this->messaging->send($message);
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
	}
}