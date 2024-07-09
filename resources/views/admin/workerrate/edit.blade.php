@extends('layouts.master')

@section('title','Company')
@section('content')

<div class="container-fluid px-4">

    <div class="card mt-4">
        <div class="card-header">
        <h4 class=""> Edit wokrer rate</h1>
        </div>
        <div class="card-body">

        @if($errors -> any())
        <div class="alert alert-danger">
        @foreach($errors -> all() as $error)
        <div>{{$error}}</div>
        @endforeach
        </div>
        @endif
            <form action="{{url('admin/update-clientrate/' .$worker_rate -> id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label >amount</label>
                    <input type="number" name="amount" value="{{$worker_rate -> amount}}"  class="form-control">
                </div>

                <div class="mb-3">
                    <label >day</label>
                    <input type="text" name="day" value="{{$worker_rate -> day}}" class="form-control" readonly>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Update employee</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection