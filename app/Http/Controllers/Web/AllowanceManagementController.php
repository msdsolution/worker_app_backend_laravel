<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AllowancekilometerFormRequest;
use App\Models\TranspotationKmRate;
use Illuminate\Http\Request;

class AllowanceManagementController extends Controller
{
    public function index()
    {
        $allowance = TranspotationKmRate::all();
        return view('admin.allowance.index',compact('allowance'));
    }
    public function edit($kilomrate_id)
    {
        $kilomrate= TranspotationKmRate::find($kilomrate_id);
        return view('admin.allowance.edit',compact('kilomrate'));
    }
    public function update(AllowancekilometerFormRequest $request,$kilomrate_id)
    {
        $data = $request -> validated();

        $kilomrate = TranspotationKmRate::find($kilomrate_id);
        $kilomrate -> km = $data['km'];
        $kilomrate -> amount = $data['amount'];
        $kilomrate -> update();
        return redirect('admin/allowance-management') -> with('message','Extended rates and hours Updated Successfully');
    }
}
