

@extends('layouts.master')

@section('title','Rata Mithuro')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
    </ol>
    <div class="row">
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <div class="card text-white text-center  " style="background-color: #559163;">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 44px;">{{$jobCount}}</h5>
                    <p class="card-text">Total Work(Current Month)</p>
                </div>
                <div class="card-footer text-center font-weight-bold">
                    <a href="{{url('admin/joblisting')}}" class="text-white"><span>View Details <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span></a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <div class="card bg-success text-white text-center">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 44px;">{{$Assignedworkcount}}</h5>
                    <p class="card-text">Assigned Work(Current month)</p>
                </div>
                <div class="card-footer text-center font-weight-bold">
                    <a href="{{url('admin/joblisting')}}" class="text-white"><span>View Details <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span></a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <div class="card bg-warning  text-white text-center">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 44px;">{{$Pendingworkcount }}</h5>
                    <p class="card-text">Pending Work</p>
                </div>
                <div class="card-footer text-center font-weight-bold">
                    <a href="{{url('admin/joblisting')}}" class="text-white"><span>View Details <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-lg-3">
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <div class="card bg-danger text-white text-center">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 44px;">{{$Rejectedworkcount}}</h5>
                    <p class="card-text">Rejected Work</p>
                </div>
                <div class="card-footer text-center font-weight-bold">
                    <a href="{{url('admin/joblisting')}}" class="text-white"><span>View Details <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span></a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <div class="card  text-white text-center"style="background-color: #2980b9">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 44px;">{{$Completedworkcount}}</h5>
                    <p class="card-text">Completed works <strong>Unpaid</strong></p>
                </div>
                <div class="card-footer text-center font-weight-bold">
                    <a href="{{url('admin/joblisting')}}" class="text-white"><span>View Details <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span></a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <div class="card bg-info text-white text-center">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 44px;">{{$CompletedworkPaidcount}}</h5>
                    <p class="card-text">Completed works <strong>Paid</strong></p>
                </div>
                <div class="card-footer text-center font-weight-bold">
                    <a href="{{url('admin/joblisting')}}" class="text-white"><span>View Details <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span></a>
                </div>
            </div>
        </div>

    </div>



    <!-- <div class="row mt-lg-3">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Area Chart Example
                </div>
                <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Bar Chart Example
                </div>
                <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
    </div> -->
</div>
@endsection
