<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExtendedhourFormRequest;
use Illuminate\Http\Request;
use App\Models\extended_hour;
use App\Models\RefferalExtendedHrRate;
use App\Models\WorkerExtendedHrRate;

class extendedhourController extends Controller
{
    //
    public function index()
    {
        $exted_hr = WorkerExtendedHrRate::all();
        return view('admin.extendedhour.index', compact('exted_hr'))->with('type', 'worker');
    }

    public function indexclient()
    {
        $exted_hr = RefferalExtendedHrRate::all();
        return view('admin.extendedhour.index', compact('exted_hr'))->with('type', 'client');
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
        return redirect('admin/extended-hour-worker') -> with('message','Client Rate Added Successfully');
    }

    public function edit($extd_hri_d)
    {
        $exted_hr = WorkerExtendedHrRate::find($extd_hri_d);
        $type = 'worker'; // Specify type
        return view('admin.extendedhour.edit', compact('exted_hr', 'type'));
    }
    
    public function editclientextdhr($extd_hri_d)
    {
        $exted_hr = RefferalExtendedHrRate::find($extd_hri_d);
        $type = 'client'; // Specify type
        return view('admin.extendedhour.edit', compact('exted_hr', 'type'));
    }
    
    public function update(ExtendedhourFormRequest $request, $extd_hri_d)
    {
        $data = $request->validated();
    
        $exted_hr = WorkerExtendedHrRate::find($extd_hri_d);
        $exted_hr->hr_extended = $data['hour_extended']; // Update based on type
        $exted_hr->amount = $data['amount'];
        $exted_hr->save();
        
        return redirect('admin/extended-hour-worker')->with('message', 'Extended rates and hours Updated Successfully');
    }
    
    public function updateclientextdhr(ExtendedhourFormRequest $request, $extd_hri_d)
    {
        $data = $request->validated();
    
        $exted_hr = RefferalExtendedHrRate::find($extd_hri_d);
        $exted_hr->hr_extended = $data['hour_extended']; // Update based on type
        $exted_hr->amount = $data['amount'];
        $exted_hr->save();
        
        return redirect('admin/extended-hour-client')->with('message', 'Client Extended rates Updated Successfully');
    }
    public function destroy($extd_hri_d)
    {
        $exted_hr = extended_hour::find($extd_hri_d);
        $exted_hr -> delete();
        return redirect('admin/extended-hour-worker') -> with('message','Extended rates and hours Deleted Successfully');
    }
}
