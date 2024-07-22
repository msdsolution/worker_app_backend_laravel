<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExtendedhourFormRequest;
use Illuminate\Http\Request;
use App\Models\extended_hour;

class extendedhourController extends Controller
{
    //
    public function index()
    {
        $exted_hr = extended_hour::all();
        return view('admin.extendedhour.index',compact('exted_hr'));
    }
    public function create()
    {
        return view('admin.extendedhour.add');
    }
    public function store(ExtendedhourFormRequest $request)
    {
        $data = $request -> validated();

        $exted_hr = new extended_hour();
        $exted_hr -> hour_extended = $data['hour_extended'];
        $exted_hr -> amount = $data['amount'];
        $exted_hr -> save();
        return redirect('admin/extended-hour') -> with('message','Client Rate Added Successfully');
    }
}
