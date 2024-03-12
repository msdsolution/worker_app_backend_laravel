<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerRateFormRequest;
use App\Models\Worker_rate;
use Illuminate\Http\Request;

class workerRateController extends Controller
{
    public function index()
    {
        $worker_rate = Worker_rate::all();
        return view('admin.workerrate.index',compact('worker_rate'));
    }
    public function create()
    {
        return view('admin.workerrate.create');
    }
    public function store(WorkerRateFormRequest $request)
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

        $worker_rate = new Worker_rate();
        $worker_rate -> amount = $data['amount'];
        $worker_rate -> day = $data['day'];
        $worker_rate -> save();
        return redirect('admin/worker_rate') -> with('message','Worker Rate Added Successfully');
    }
    public function edit($workerrate_id)
    {
  
        $worker_rate = Worker_rate::find($workerrate_id);
        return view('admin.workerrate.edit',compact('worker_rate'));
    }
    public function update(WorkerRateFormRequest $request,$workerrate_id)
    {

        $data = $request -> validated();

        $worker_rate = Worker_rate::find($workerrate_id);

        $worker_rate -> amount = $data['amount'];
        $worker_rate -> day = $data['day'];;

        $worker_rate -> update();

        return redirect('admin/worker_rate') -> with('message','Worker rates Updated Successfully');
    }
    public function destroy($workerrate_id)
    {
        $workerrate_id = Worker_rate::find($workerrate_id);
        $workerrate_id -> delete();
        return redirect('admin/worker_rate') -> with('message','Worker rate Deleted Successfully');
    }
}
