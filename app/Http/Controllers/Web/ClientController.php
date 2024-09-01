<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index()
    {
        // $clients = User::where('user_type', 2)->get();
        // return view('admin.client.index', compact('clients'));
        // $clients = User::all();
        // //return 'Hey';
        // return view('admin.client.index',compact('clients'));
        $clients = User::withTrashed()  // Include soft-deleted records
        ->where('user_type', 2)
        ->orderBy('created_at', 'desc') // Order by creation date
        ->get();

    return view('admin.client.index', compact('clients'));
    }
    public function create()
    {
        return view('admin.client.create');
    }
    public function store(ClientFormRequest $request)
    {
        $data = $request -> validated();

        $client = new User();
        $client -> first_name = $data['first_name'];
        $client -> last_name = $data['last_name'];
        $client -> email = $data['email'];
        $client -> password = Hash::make($data['password']);
        $client -> location = $data['location'];
        $client -> user_address = $data['user_address'];
        $client -> phone_no = $data['phone_no'];
        $client->user_type = 2;
        $client -> save();

        $documentMap = [
            'identity_card' => 1,
            'police_clearance' => 2,
            'gramasevaka_certificate' => 3,
            'driver_license' => 4,
            'vehicle_insurance' => 5,
            'passport' => 6,
        ];
    
        // Loop through each document field
        foreach ($documentMap as $fieldName => $docId) {
            if ($request->hasFile($fieldName)) {
                $filePath = $request->file($fieldName)->store('userDocAttachment', 'public');
    
                // Save the document details in user_documents table
                DB::table('user_documents')->insert([
                    'user_id' => $client->id,
                    'doc_id' => $docId,
                    'doc_url' => $filePath,
                ]);
            }
        }


        return redirect('admin/client') -> with('message','Client Added Successfully');
    }
    public function edit($client_id)
    {
  
        $client = User::find($client_id);


        $documentMap = [
            'identity_card' => 1,
            'police_clearance' => 2,
            'gramasevaka_certificate' => 3,
            'driver_license' => 4,
            'vehicle_insurance' => 5,
            'passport' => 6,
        ];
    
        $documents = DB::table('user_documents')
            ->where('user_id', $client->id)
            ->get();
    
        // Convert documents to a key-value array for easier access in the view
        $documents = $documents->keyBy('doc_id');
    
        return view('admin.client.edit', compact('client', 'documents', 'documentMap'));

      //  return view('admin.client.edit',compact('client'));
    }
    public function update(ClientFormRequest $request,$client_id)
    {

        $data = $request -> validated();

        $client = user::find($client_id);
        $client -> first_name = $data['first_name'];
        $client -> last_name = $data['last_name'];
        $client -> email = $data['email'];
        $client -> password = Hash::make($data['password']);
        $client -> user_address = $data['user_address'];
        $client -> phone_no = $data['phone_no'];
        $client -> update();


        $documentMap = [
            'identity_card' => 1,
            'police_clearance' => 2,
            'gramasevaka_certificate' => 3,
            'driver_license' => 4,
            'vehicle_insurance' => 5,
            'passport' => 6,
        ];
    
        // Loop through each document field
        foreach ($documentMap as $fieldName => $docId) {
            if ($request->hasFile($fieldName)) {
                // Check if there's an existing record for this document
                
                $existingDocument = DB::table('user_documents')
                    ->where('user_id', $client->id)
                    ->where('doc_id', $docId)
                    ->first();
                if ($existingDocument) {
                    // Delete the old file if needed
                    if ($existingDocument->doc_url) {
                        Storage::disk('public')->delete($existingDocument->doc_url);
                    }
    
                    // Update the existing record with the new file
                    $filePath = $request->file($fieldName)->store('userDocAttachment', 'public');
    
                    DB::table('user_documents')
                        ->where('id', $existingDocument->id)
                        ->update(['doc_url' => $filePath]);
                } else {
                    // If no existing record, insert a new one
                    $filePath = $request->file($fieldName)->store('userDocAttachment', 'public');
    
                    DB::table('user_documents')->insert([
                        'user_id' => $client->id,
                        'doc_id' => $docId,
                        'doc_url' => $filePath,
                    ]);
                }
            }
        }



   
        return redirect('admin/client') -> with('message','Client Updated Successfully');
    }
    public function destroy($client_id)
    {
        $client = User::find($client_id);
        $client -> delete();
        return redirect('admin/client') -> with('message','Client Deleted Successfully');
    }

    public function restore($id)
{
    $client = User::withTrashed()->find($id);
    if ($client) {
        $client->restore();
        return redirect()->back()->with('message', 'Client restored successfully');
    }
    return redirect()->back()->with('error', 'Client not found');
}
public function changeStatusemp(Request $request) {

    $client = User::find($request ->id);
    $client->status = $request->status;
    $client->save();
    return response()->json(['success' => 'Status Changed Successfully']);
}
}
