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
            <form action="{{url('admin/add-client')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label >First Name</label>
                    <input type="text" name="first_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label >Last Name</label>
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
                <div class="mb-3">
                    <label >User Address</label>
                    <input type="text" name="user_address" class="form-control">
                </div>
                <div class="mb-3">
                    <label >Phone Numeber</label>
                    <input type="text" name="phone_no" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Identity card</label>
                    <input type="file" class="form-control" id="identity_card" name="identity_card">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Police Clearance certificate</label>
                    <input type="file" class="form-control" id="police_clearance" name="police_clearance">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Gramasewaka certificate</label>
                    <input type="file" class="form-control" id="gramasevaka_certificate" name="gramasevaka_certificate">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Driving license</label>
                    <input type="file" class="form-control" id="driver_license" name="driver_license">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Vehicle insurance</label>
                    <input type="file" class="form-control" id="vehicle_insurance" name="vehicle_insurance">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Passport</label>
                    <input type="file" class="form-control" id="passport" name="passport">
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