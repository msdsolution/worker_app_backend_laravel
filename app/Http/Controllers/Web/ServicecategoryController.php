<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceCategoryFormRequest;
use App\Models\Service_Category;
use Illuminate\Http\Request;

class ServicecategoryController extends Controller
{
    public function index()
    {
        $Service_Category = Service_Category::all();
        //return 'Hey';
        return view('admin.servicecategory.index',compact('Service_Category'));
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

        return redirect('admin/servicecategory') -> with('message','Service Updated Successfully');
    }

    public function destroy($Service_Category_id)
    {
        $Service_Category = Service_Category::find($Service_Category_id);
        $Service_Category -> delete();
        return redirect('admin/servicecategory') -> with('message','Service Deleted Successfully');
    }
}
