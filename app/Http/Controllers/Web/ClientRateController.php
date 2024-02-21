<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRateFormRequest;
use App\Models\Client_rate;
use Illuminate\Http\Request;

class ClientRateController extends Controller
{
    //
    public function index()
    {
        $client_rate = Client_rate::all();
        return view('admin.clientrate.index',compact('client_rate'));
    }

    public function create()
    {
        return view('admin.clientrate.create');
    }
    public function store(ClientRateFormRequest $request)
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

        $client_rate = new Client_rate();
        $client_rate -> amount = $data['amount'];
        $client_rate -> day = $data['day'];
        $client_rate -> save();
        return redirect('admin/client_rate') -> with('message','Client Rate Added Successfully');
    }
}
