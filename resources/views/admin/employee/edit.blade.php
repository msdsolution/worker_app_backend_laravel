@extends('layouts.master')

@section('title','Company')
@section('content')

<div class="container-fluid px-4">

    <div class="card mt-4">
        <div class="card-header">
        <h4 class=""> Edit employee</h1>
        </div>
        <div class="card-body">

        @if($errors -> any())
        <div class="alert alert-danger">
        @foreach($errors -> all() as $error)
        <div>{{$error}}</div>
        @endforeach
        </div>
        @endif
            <form action="{{url('admin/update-employee/' .$employee -> id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label >First Name</label>
                    <input type="text" name="first_name" value="{{$employee -> first_name}}" class="form-control">
                </div>
                
                <div class="mb-3">
                    <label >Last Name</label>
                    <input type="text" name="last_name" value="{{$employee -> last_name}}"  class="form-control">
                </div>

                <div class="mb-3">
                    <label >Email</label>
                    <input type="text" name="email" value="{{$employee -> email}}" class="form-control">
                </div>

                <div class="mb-3">
                    <label >Password</label>
                    <input type="password" name="password" value="{{$employee -> password}}" class="form-control">
                </div>

                <div class="mb-3">
                    <label >Location</label>
                    <input type="text" name="location" value="{{$employee -> location}}" class="form-control">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Update Employee</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection