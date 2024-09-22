<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Job_Service_Cat;
use App\Models\Service_Category;
use App\Models\TimeHours;
use App\Models\RefferalRates;
use App\Models\WokerRates;
use App\Models\SriLankaDistricts;
use App\Models\ComplaintMessages;
use App\Models\ComplaintAttachment;
use App\Models\RefferalPayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use \Crypt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class PaymentIntegrationApiController extends Controller
{

	public function createPayment(Request $request)
    {

        $user = auth()->user();
    	// Validate the request
        $request->validate([
            'unique_order_id' => 'required|string',
            'total_amount' => 'required|numeric',
        ]);

        // if ($request->input('tip_amount')) {
        //     $job = Job::findOrFail($request->input('unique_order_id'));
        //     $job->is_worker_tip = 1;
        //     $job->worker_tip_amount = $request->input('tip_amount');
        //     $job->save();
        // }

        $uniqueOrderId = $request->input('unique_order_id');
        $totalAmount = $request->input('total_amount');
        $plaintext = "{$uniqueOrderId}|{$totalAmount}";

       	try {

            // Prepare the checkout URL with the encrypted data
            $checkoutUrl = 'https://webxpay.com/index.php?route=checkout/billing';
            $data = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'contact_number' => $user->phone_no,
                'address_line_one' => $user->user_address,
                'address_line_two' => '',
                'city' => '',
                'state' => '',
                'postal_code' => '',
                'country' => '',
                'custom_feilds' => '',
                'cms' => 'PHP',
                'process_currency' => 'LKR',
                'secret_key' => '147d0d99-feb9-4080-baa4-c39114d6611c',
                'plaintext' => $plaintext,
            ];

            // Convert to JSON
            $jsonString = json_encode($data);
            // Base64 encode the JSON string
            $base64String = base64_encode($jsonString);

            $redirectUrl = 'https://ratamithuro.com/api/redirect/' . urlencode($base64String);

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => $redirectUrl,
            ], 200);
            //return response()->json(['encrypted_data' => $encryptedData]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function handleRedirect($encodedData)
    {

        $url = 'https://stagingxpay.info/index.php?route=checkout/billing'; // Replace with your form action URL

            // Decode the data
            $jsonString = base64_decode($encodedData);
        
            $data = json_decode($jsonString, true);
            //dd($data['payment']);

            //$plainText = $data['plaintext'];
            //dd($plainText);

            // $encryptedData = $this->encryptionService->encrypt($plainText);
            // $payment = $encryptedData;
            //dd($plainText);

            $plaintext = $data['plaintext'];
            $publickey = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDKyDJZXtmqe2GUBulJDkjvQoaC
43ZUPS0d9LRGfLQNw3G4yMYPkPm91/EdArgZ6wkBO/ZJISd+coIp9dcwrvK9gXBh
z+W9UJV43kaoJ1w4MDn0VjgQE7FHTpQgU59ncfglBOC3MXQ01Mm+96ovYnsRBDZo
VBGYCZ5APiEyipPLiQIDAQAB
-----END PUBLIC KEY-----";
            //load public key for encrypting
            openssl_public_encrypt($plaintext, $encrypt, $publickey);

            //encode for data passing
            $payment = base64_encode($encrypt);

            //dd($data);


            $html = '<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>WEBXPAY | Sample Checkout Form</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f4f4f4;
                                margin: 0;
                                padding: 0;
                            }
                            form {
                                max-width: 600px;
                                margin: 50px auto;
                                padding: 20px;
                                background-color: #fff;
                                border-radius: 8px;
                                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                            }
                            h1 {
                                text-align: center;
                                margin-bottom: 20px;
                            }
                            .form-group {
                                margin-bottom: 15px;
                            }
                            .form-group label {
                                display: block;
                                margin-bottom: 5px;
                                font-weight: bold;
                            }
                            .form-group input[type="text"] {
                                width: calc(100% - 22px);
                                padding: 10px;
                                border: 1px solid #ddd;
                                border-radius: 4px;
                                font-size: 16px;
                                box-sizing: border-box;
                            }
                            .form-group input[readonly], 
                            .form-group input[disabled] {
                                background-color: #f9f9f9;
                            }
                            .form-group input[type="submit"] {
                                width: 100%;
                                padding: 10px;
                                border: none;
                                border-radius: 4px;
                                background-color: #007bff;
                                color: #fff;
                                font-size: 18px;
                                cursor: pointer;
                            }
                            .form-group input[type="submit"]:hover {
                                background-color: #0056b3;
                            }
                        </style>
                    </head>
                    <body>
                        <form action="' . htmlspecialchars($url) . '" method="POST">
                            <h1>Checkout Form</h1>
                            <div class="form-group">
                                <label for="first_name">First name:</label>
                                <input type="text" id="first_name" name="first_name" value="' . htmlspecialchars($data['last_name']) . '" readonly>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last name:</label>
                                <input type="text" id="last_name" name="last_name" value="' . htmlspecialchars($data['last_name']) . '" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" id="email" name="email" value="' . htmlspecialchars($data['email']) . '" readonly>
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Contact Number:</label>
                                <input type="text" id="contact_number" name="contact_number" value="' . htmlspecialchars($data['contact_number']) . '" readonly>
                            </div>
                            <div class="form-group">
                                <label for="address_line_one">Address Line 1:</label>
                                <input type="text" id="address_line_one" name="address_line_one" value="' . htmlspecialchars($data['address_line_one']) . '" readonly>
                            </div>
                            
                            <div class="form-group">
                                <!-- <label for="cms">CMS:</label> -->
                                <input type="hidden" id="cms" name="cms" value="' . htmlspecialchars($data['cms']) . '" disabled>
                            </div>
                            <div class="form-group">
                                <label for="process_currency">Currency:</label>
                                <input type="text" id="process_currency" name="process_currency" value="LKR" readonly>
                            </div>
                            <input type="hidden" name="enc_method" value="JCs3J+6oSz4V0LgE0zi/Bg==">
                            <input type="hidden" name="secret_key" value="147d0d99-feb9-4080-baa4-c39114d6611c">
                            <input type="hidden" name="payment" value="' . htmlspecialchars($payment) . '">
                            <div class="form-group">
                                <input type="submit" value="Pay Now">
                            </div>
                        </form>
                    </body>
                    </html>';

                    // <div class="form-group">
                    //             <label for="address_line_two">Address Line 2:</label>
                    //             <input type="hidden" id="address_line_two" name="address_line_two" value="' . htmlspecialchars($data['address_line_two']) . '" disabled>
                    //         </div>
                    //         <div class="form-group">
                    //             <label for="city">City:</label>
                    //             <input type="hidden" id="city" name="city" value="' . htmlspecialchars($data['city']) . '" disabled>
                    //         </div>
                    //         <div class="form-group">
                    //             <label for="state">State:</label>
                    //             <input type="hidden" id="state" name="state" value="' . htmlspecialchars($data['state']) . '" disabled>
                    //         </div>
                    //         <div class="form-group">
                    //             <label for="postal_code">Zip/Postal Code:</label>
                    //             <input type="hidden" id="postal_code" name="postal_code" value="' . htmlspecialchars($data['postal_code']) . '" disabled>
                    //         </div>
                    //         <div class="form-group">
                    //             <label for="country">Country:</label>
                    //             <input type="hidden" id="country" name="country" value="' . htmlspecialchars($data['country']) . '" disabled>
                    //         </div>

            return response($html, 200)->header('Content-Type', 'text/html');
    }

    public function webxpayResponse(Request $request)
    {
        //decode & get POST parameters
        $payment = base64_decode($request->input('payment'));
        $signature = base64_decode($request->input('signature'));
        $custom_fields = base64_decode($request->input('custom_fields'));
        //load public key for signature matching
        $publickey = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDKyDJZXtmqe2GUBulJDkjvQoaC
43ZUPS0d9LRGfLQNw3G4yMYPkPm91/EdArgZ6wkBO/ZJISd+coIp9dcwrvK9gXBh
z+W9UJV43kaoJ1w4MDn0VjgQE7FHTpQgU59ncfglBOC3MXQ01Mm+96ovYnsRBDZo
VBGYCZ5APiEyipPLiQIDAQAB
-----END PUBLIC KEY-----";

        openssl_public_decrypt($signature, $value, $publickey);

        $signature_status = false ;

        if($value == $payment){
            $signature_status = true ;
        }

        //get payment response in segments
        //payment format: order_id|order_refference_number|date_time_transaction|payment_gateway_used|status_code|comment;
        $responseVariables = explode('|', $payment);      

        if($signature_status == true)
        {
            $job = Job::findOrFail($responseVariables[0]);

            $referalAmount = DB::table('job_service_cat')
                ->select('refferal_amount')
                ->where('job_id', $job->id)
                ->first();

            $extendedHourAmount = 0;

            // If the job is extended, calculate the extended hour amount
            if ($job->is_extended == 1) {
                // Get the amount for the extended hours
                $extendedHourRate = DB::table('extended_hour')
                    ->select('amount')
                    ->first(); // Assuming amount is constant for all extended hours
                
                // Calculate extended hour amount
                if ($extendedHourRate) {
                    $extendedHourAmount = $extendedHourRate->amount * $job->extended_hrs;
                }
            }

            // Calculate grand total
            $grandTotal = ($referalAmount->refferal_amount ?? 0) + $extendedHourAmount;

            if ($responseVariables[6] > $grandTotal) {
                $job->is_worker_tip = 1;
                $job->worker_tip_amount = $responseVariables[6] - $grandTotal;
                $job->save();
            }

            $refferalPayment = RefferalPayment::create([
                'job_id' => $responseVariables[0],
                'order_id' => $responseVariables[0],
                'amount' => $responseVariables[6],
                'status' => 1,
                'order_refference_number' => $responseVariables[1],
                'date_time_transaction' => $responseVariables[2],
                'payment_gateway_used' => $responseVariables[3],
                'status_code' => $responseVariables[4],
                'comment' => $responseVariables[5],
            ]);

            $job->status = 5;
            $job->save();

            $this->sendInvoice($job);

            $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Payment Success</title>
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
                    <h2>Payment Success</h2>
                    <p>Your payment was processed successfully!</p>
                    <p>Please close the browser and open Ratamithuro app</p>
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


            return response($html, 200)->header('Content-Type', 'text/html');
            //display values
            // return response()->json(['status' => 200, 'success' => true,'message' => 'Payment made successfully', 'payment' => $responseVariables], 200);
            //dd($signature_status);
            //$custom_fields_varible = explode('|', $custom_fields);
            //dd($custom_fields_varible);
            //dd($responseVariables);
        }else
        {

            // if ($request->input('tip_amount')) {
            //     $job = Job::findOrFail($request->input('unique_order_id'));

            //     $job->is_worker_tip = 1;
            //     $job->worker_tip_amount = $request->input('tip_amount');

            //     $job->save();
            // }

            $html = '<!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>Payment Failed</title>
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
                                <h2>Payment Failed</h2>
                                <p>Your payment was Failed!</p>
                                <p>Close the browser and try again through Ratamithuro app.</p>
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

                        return response($html, 200)->header('Content-Type', 'text/html');
            //return response()->json(['status' => 500, 'success' => true,'message' => 'Payment Failed'], 500);
            //dd('Error Validation'); 
        }
    }

    public function sendInvoice(Job $job_data)
    {
        $refferal = User::findOrFail($job_data->user_id);
        $clientName = $refferal->first_name." ".$refferal->last_name;
        $clientEmail = $refferal->email;
        $message = "Thank you for your payment.";

        // Retrieve job details
        $job = DB::table('job')
            ->select(
                'job.id as jobId',
                'job_no',
                'job.user_id',
                'users.first_name as userFirstName',
                'users.last_name as userLastName',
                'users.email as Email',
                'users.user_address as Address',
                'users.phone_no as Phonenumber',
                'job.description as jobDescription',
                'job.city_id',
                'cities.name_en as cityName',
                'job.start_location',
                'job.end_location',
                'job.worker_id',
                'workers.first_name as workerName',
                'job.status',
                'job.required_date',
                'job.required_time',
                'job.created_at',
                'job.preferred_sex',
                'job.is_extended',
                'job.extended_hrs',
                'job.is_worker_tip',
                'job.worker_tip_amount'
            )
            ->leftJoin('users', 'job.user_id', '=', 'users.id')
            ->leftJoin('users as workers', 'job.worker_id', '=', 'workers.id')
            ->leftJoin('cities', 'job.city_id', '=', 'cities.id')
            ->where('job.id', $job_data->id)
            ->first();

        // Retrieve all service categories associated with the job
        $serviceCategories = DB::table('job_service_cat')
            ->join('service_cat', 'job_service_cat.service_cat_id', '=', 'service_cat.id')
            ->select('service_cat.name')
            ->where('job_service_cat.job_id', $job_data->id)
            ->get();

        // Concatenate service category names into a single string
        $categoryNames = $serviceCategories->pluck('name')->implode(', ');

        // Retrieve referral amount associated with the job
        $referalAmount = DB::table('job_service_cat')
            ->select('refferal_amount')
            ->where('job_id', $job_data->id)
            ->first();

        // Initialize extended hour amount and extended status
        $extendedHourAmount = 0;
        $workerTipAmount = 0; 
        $isExtended = $job->is_extended ? 'Yes' : 'No';

        // If the job is extended, calculate the extended hour amount
        if ($job->is_extended) {
            // Get the amount for the extended hours
            $extendedHourRate = DB::table('extended_hour')
                ->select('amount')
                ->first(); // Assuming amount is constant for all extended hours

            // Calculate extended hour amount
            if ($extendedHourRate) {
                $extendedHourAmount = $extendedHourRate->amount * $job->extended_hrs;
            }
        }

        if ($job->is_worker_tip == 1) {
            $workerTipAmount = $job->worker_tip_amount;
        }
        // Calculate grand total
        $grandTotal = ($referalAmount->refferal_amount ?? 0) + $extendedHourAmount + $workerTipAmount;

        // Generate the PDF
        $pdf = Pdf::loadView('admin.invoice.invoice', [
            'job' => $job,
            'categoryNames' => $categoryNames,
            'referalAmount' => $referalAmount,
            'isExtended' => $isExtended,
            'extendedHourAmount' => $extendedHourAmount,
            'workerTipAmount' => $workerTipAmount,
            'grandTotal' => $grandTotal
        ]);

        $pdfPath = storage_path('app/invoice.pdf');
        $pdf->save($pdfPath);

        // Send email
        Mail::to($clientEmail)->send(new InvoiceMail($clientName, $message, $pdfPath));

        // Optionally, you can return a response or redirect
        //return redirect()->back()->with('success', 'Invoice sent successfully!');
    }

}