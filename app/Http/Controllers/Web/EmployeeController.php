<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        // $employees = User::all();
        // //return 'Hey';
        // return view('admin.employee.index',compact('employees'));


        $employees = User::withTrashed()
        ->where('user_type', 3)
        ->orderBy('created_at', 'desc') 
        ->get();
        return view('admin.employee.index', compact('employees'));

    }
    public function create()
    {
        return view('admin.employee.create');
    }
    public function store(EmployeeFormRequest $request)
    {
        // $data = $request -> validated();

        // $employee = new User();

        // $employee -> company_id = $data['company_id'];
        // $employee -> firstname = $data['firstname'];
        // $employee -> lastname = $data['lastname'];
        // $employee -> email = $data['email'];
        // $employee -> phone = $data['phone'];
        // $employee -> save();
        // return redirect('admin/employees') -> with('message','Employee Added Successfully');
        $data = $request -> validated();

        $employee = new User();
        $employee -> first_name = $data['first_name'];
        $employee -> last_name = $data['last_name'];
        $employee -> email = $data['email'];
        $employee -> password = Hash::make($data['password']);
        $employee -> location = $data['location'];
        $employee -> user_address = $data['user_address'];
        $employee -> phone_no = $data['phone_no'];
       // $employee->status = 1;
        $employee->user_type = 3;
        $employee -> save();


        $documentMap = [
            'identity_card_front' => 1,
            'police_clearance' => 2,
            'gramasevaka_certificate' => 3,
            'driver_license' => 4,
            'vehicle_insurance_front' => 5,
            'passport' => 6,
            'identity_card_back' => 7,
            'driver_license_back' => 8,
            'vehicle_insurance_back' => 9,
        ];
    
        // Loop through each document field
        foreach ($documentMap as $fieldName => $docId) {
            if ($request->hasFile($fieldName)) {
                $filePath = $request->file($fieldName)->store('userDocAttachment', 'public');
    
                // Save the document details in user_documents table
                DB::table('user_documents')->insert([
                    'user_id' => $employee->id,
                    'doc_id' => $docId,
                    'doc_url' => $filePath,
                ]);
            }
        }


        return redirect('admin/employees') -> with('message','Employee Added Successfully');
    }
    public function edit($employee_id)
    {
  
        $employee = User::find($employee_id);

        $documentMap = [
            'identity_card_front' => 1,
            'identity_card_back' => 7,
            'police_clearance' => 2,
            'gramasevaka_certificate' => 3,
            'driver_license' => 4,
            'driver_license_back' => 8,
            'vehicle_insurance_front' => 5,
            'vehicle_insurance_back' => 9,
            'passport' => 6,
        ];
    
        $documents = DB::table('user_documents')
            ->where('user_id', $employee->id)
            ->get();
    
        // Convert documents to a key-value array for easier access in the view
        $documents = $documents->keyBy('doc_id');

      //  return view('admin.employee.edit',compact('employee'));
        return view('admin.employee.edit', compact('employee', 'documents', 'documentMap'));
    }
    public function update(EmployeeFormRequest $request,$employee_id)
    {

        $data = $request -> validated();

        $employee = user::find($employee_id);

        $employee -> first_name = $data['first_name'];
        $employee -> last_name = $data['last_name'];
        $employee -> email = $data['email'];
        $employee -> password = Hash::make($data['password']);
       $employee -> location = $data['location']; 
       $employee -> user_address = $data['user_address']; 
       $employee -> phone_no = $data['phone_no']; 

        $employee -> update();

        $documentMap = [
            'identity_card_front' => 1,
            'identity_card_back' => 7,
            'police_clearance' => 2,
            'gramasevaka_certificate' => 3,
            'driver_license' => 4,
            'driver_license_back' => 8,
            'vehicle_insurance_front' => 5,
            'vehicle_insurance_back' => 9,
            'passport' => 6,
        ];
    
        // Loop through each document field
        foreach ($documentMap as $fieldName => $docId) {
            if ($request->hasFile($fieldName)) {
                // Check if there's an existing record for this document
                
                $existingDocument = DB::table('user_documents')
                    ->where('user_id', $employee->id)
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
                        'user_id' => $employee->id,
                        'doc_id' => $docId,
                        'doc_url' => $filePath,
                    ]);
                }
            }
        }


        return redirect('admin/employees') -> with('message','Employee Updated Successfully');
    }
    public function destroy($employee_id)
    {
        $employee = User::find($employee_id);
        $employee -> delete();
        return redirect('admin/employees') -> with('message','Employee Deleted Successfully');
    }
    public function changeStatusemp(Request $request) {

        $employee = User::find($request ->id);
        $employee->status = $request->status;
        $employee->save();
        return response()->json(['success' => 'Status Changed Successfully']);
    }
    public function restore($id)
    {
        $employee = User::withTrashed()->find($id);
        if ($employee) {
            $employee->restore();
            return redirect()->back()->with('message', 'Employee restored successfully');
        }
        return redirect()->back()->with('error', 'Employee not found');
    }



//     public function deleteDocument($documentId)
// {
//     // Retrieve the document from the database
//     $document = DB::table('user_documents')->where('id', $documentId)->first();

//     if ($document) {
//         // Delete the file from storage
//         if (Storage::disk('public')->exists($document->doc_url)) {
//             Storage::disk('public')->delete($document->doc_url);
//         }

//         // Delete the record from the database
//         DB::table('user_documents')->where('id', $documentId)->delete();

//         return redirect()->back()->with('message', 'Document deleted successfully');
//     }

//     return redirect()->back()->with('error', 'Document not found');
// }
public function deleteDocument($documentId)
{
    // $document = DB::table('user_documents')->where('id', $documentId)->first();
    // if ($document) {
    //     Storage::delete('public/' . $document->doc_url); // Deletes the file from storage
    //     $document->delete(); // Deletes the record from the database
    //     return response()->json(['success' => 'Document deleted successfully.']);
    // }

    // return response()->json(['error' => 'Document not found.'], 404);
    $document = DB::table('user_documents')->where('id', $documentId)->first();
    if ($document) {
        Storage::delete('public/' . $document->doc_url); // Deletes the file from storage
        DB::table('user_documents')->where('id', $documentId)->delete(); // Deletes the record from the database
        return response()->json(['success' => 'Document deleted successfully.']);
    }

    return response()->json(['error' => 'Document not found.'], 404);
}

}
