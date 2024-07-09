<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        // $employees = User::all();
        // //return 'Hey';
        // return view('admin.employee.index',compact('employees'));


        $employees = User::where('user_type', 3)->get();
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
        $employee->user_type = 3;
        $employee -> save();
        return redirect('admin/employees') -> with('message','Employee Added Successfully');
    }
    public function edit($employee_id)
    {
  
        $employee = User::find($employee_id);
        return view('admin.employee.edit',compact('employee'));
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

        $employee -> update();

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
}
