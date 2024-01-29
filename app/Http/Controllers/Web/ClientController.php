<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index()
    {
        $clients = User::where('user_type', 2)->get();
        return view('admin.client.index', compact('clients'));
        // $clients = User::all();
        // //return 'Hey';
        // return view('admin.client.index',compact('clients'));
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
        $client->user_type = 2;
        $client -> save();
        return redirect('admin/client') -> with('message','Client Added Successfully');
    }
    public function edit($client_id)
    {
  
        $client = User::find($client_id);
        return view('admin.employee.edit',compact('employee','company'));
    }
    public function update(ClientFormRequest $request,$client_id)
    {

        $data = $request -> validated();

        $client = user::find($client_id);
        $client -> firstname = $data['firstname'];
        $client -> lastname = $data['lastname'];
        $client -> email = $data['email'];
        $client -> phone = $data['phone'];
        $client -> update();

        return redirect('admin/employees') -> with('message','Employee Updated Successfully');
    }
    public function destroy($employee_id)
    {
        $client = User::find($employee_id);
        $client -> delete();
        return redirect('admin/employees') -> with('message','Employee Deleted Successfully');
    }
}
