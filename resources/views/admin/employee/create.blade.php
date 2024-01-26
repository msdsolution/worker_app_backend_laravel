@extends('layouts.master')

@section('title','Company')
@section('content')

<div class="container-fluid px-4">

    <div class="card mt-4">
        <div class="card-header">
        <h4 class=""> Add Client</h1>
        </div>
        <div class="card-body">

        @if($errors -> any())
        <div class="alert alert-danger">
        @foreach($errors -> all() as $error)
        <div>{{$error}}</div>
        @endforeach
        </div>
        @endif
            <form action="{{url('admin/add-employee')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label >first_name</label>
                    <input type="text" name="first_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label >last_name</label>
                    <input type="text" name="last_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label >Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label >Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label >Location</label>
                    <input type="text" name="location" class="form-control">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Save Client</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection