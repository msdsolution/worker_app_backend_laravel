@extends('layouts.master')

@section('title','Company')
@section('content')

<div class="container-fluid px-4">

    <div class="card mt-4">
        <div class="card-header">
        <h4 class=""> Add Rates for Worker</h1>
        </div>
        <div class="card-body">

        @if($errors -> any())
        <div class="alert alert-danger">
        @foreach($errors -> all() as $error)
        <div>{{$error}}</div>
        @endforeach
        </div>
        @endif
            <form action="{{url('admin/add-worker_rate')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label >Amount</label>
                    <input type="number" name="amount" class="form-control">
                </div>
                <div class="mb-3">
                    <label >Day</label>
                    <input type="text" name="day" class="form-control">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Save Rate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection