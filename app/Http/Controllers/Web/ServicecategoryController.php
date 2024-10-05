<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceCategoryFormRequest;
use App\Models\Service_Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ServicecategoryController extends Controller
{
    public function index()
    {
      //  $Service_Category = Service_Category::all();
        $Service_Category = Service_Category::withTrashed()->get();
        //return 'Hey';
        return view('admin.servicecategory.index',compact('Service_Category'));




        //     $employees = User::withTrashed()
        // ->where('user_type', 3)
        // ->orderBy('created_at', 'desc') 
        // ->get();
        // return view('admin.employee.index', compact('employees'));
    }
    public function create()
    {
        return view('admin.servicecategory.create');
    }
    public function store(ServiceCategoryFormRequest $request)
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

        $Service_Category = new Service_Category();
        $Service_Category -> name = $data['name'];
        $Service_Category -> description = $data['description'];
        $Service_Category -> save();

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            foreach ($attachments as $attachment) {
                // Generate a unique file name
                // Store the file in storage/app/complaint_attachments
                $filePath = $attachment->store('servicecategoryIcons', 'public');
                // Save attachment info in the database
                $Service_Category->img_icon_url = $filePath; // Path relative to storage/app
                $Service_Category->update();
            }
        }

        return redirect('admin/servicecategory') -> with('message','Service Added Successfully');
    }
    public function edit($Service_Category_id)
    {
        $Service_Category = Service_Category::find($Service_Category_id);
        return view('admin.servicecategory.edit',compact('Service_Category'));
    }
    public function update(ServiceCategoryFormRequest $request,$Service_Category_id)
    {

        $data = $request -> validated();

        $Service_Category = Service_Category::find($Service_Category_id);

        $Service_Category -> name = $data['name'];
        $Service_Category -> description = $data['description'];
        $Service_Category -> update();

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            foreach ($attachments as $attachment) {
                // if icon exit then delete from eserver and update new icon
                if ($Service_Category->img_icon_url) {
                    Storage::delete('public/' . $Service_Category->img_icon_url); // Deletes the file from storage
                    $Service_Category->img_icon_url = null; // Path relative to storage/app
                    $Service_Category->update();
                }
                // Store the file in storage/app/servicecategoryIcons
                $filePath = $attachment->store('servicecategoryIcons', 'public');
                // Save attachment info in the database
                $Service_Category->img_icon_url = $filePath; // Path relative to storage/app
                $Service_Category->update();
            }
        }

        return redirect('admin/servicecategory') -> with('message','Service Updated Successfully');
    }

    public function destroy($Service_Category_id)
    {
        $Service_Category = Service_Category::find($Service_Category_id);
        $Service_Category -> delete();
        return redirect('admin/servicecategory') -> with('message','Service Deleted Successfully');
    }
    public function restore($id)
    {
        $Service_Category = Service_Category::withTrashed()->find($id);
        if ($Service_Category) {
            $Service_Category->restore();
            return redirect()->back()->with('message', 'Service category restored successfully');
        }
        return redirect()->back()->with('error', 'Service category not found');
    }

    public function deleteIcon($serviceCatId)
    {
        $servicecategory = Service_Category::find($serviceCatId);
        if ($servicecategory) {
            Storage::delete('public/' . $servicecategory->img_icon_url); // Deletes the file from storage
            $servicecategory->img_icon_url = null; // null url
            $servicecategory->update();
            return response()->json(['success' => 'Icon removed successfully.']);
        }

        return response()->json(['error' => 'Icon not found.'], 404);
    }
}
