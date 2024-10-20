@extends('layouts.master')

@section('title','Company')
@section('content')

<div class="container-fluid px-4">

    <div class="card mt-4">
        <div class="card-header">
        <h4 class="">Update Extended Hour Rates</h4>
        </div>
        <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        <div>{{$error}}</div>
        @endforeach
        </div>
        @endif

            <form action="{{ url('admin/update-extendex-hour/' . $type . '/' . $exted_hr->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Hours</label>
                    <!-- Display hour_extended for workers and hr_extended for clients -->
                    <input type="number" name="hour_extended" 
                           value="{{ $exted_hr->hour_extended ?? $exted_hr->hr_extended }}" 
                           class="form-control" readonly>
                </div>
                
                <div class="mb-3">
                    <label>Amount</label>
                    <input type="number" name="amount" value="{{ $exted_hr->amount }}" class="form-control">
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Update Extended Hours</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
