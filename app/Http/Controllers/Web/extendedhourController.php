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
    public function edit($extd_hri_d)
    {
        $exted_hr = extended_hour::find($extd_hri_d);
        return view('admin.extendedhour.edit',compact('exted_hr'));
    }
    public function update(ExtendedhourFormRequest $request,$extd_hri_d)
    {
        $data = $request -> validated();

        $exted_hr = extended_hour::find($extd_hri_d);
        $exted_hr -> hour_extended = $data['hour_extended'];
        $exted_hr -> amount = $data['amount'];
        $exted_hr -> update();
        return redirect('admin/extended-hour') -> with('message','Extended rates and hours Updated Successfully');
    }
    public function destroy($extd_hri_d)
    {
        $exted_hr = extended_hour::find($extd_hri_d);
        $exted_hr -> delete();
        return redirect('admin/extended-hour') -> with('message','Extended rates and hours Deleted Successfully');
    }
}
