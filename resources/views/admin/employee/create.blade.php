@extends('layouts.master')

@section('title','Company')
@section('content')

<div class="container-fluid px-4">

    <div class="card mt-4">
        <div class="card-header">
        <h4 class=""> Add Employee</h1>
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
                    <label >Phone Number</label>
                    <input type="text" name="phone_no" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Identity card Front</label>
                    <input type="file" class="form-control" id="identity_card_front" name="identity_card_front">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Identity card Back</label>
                    <input type="file" class="form-control" id="identity_card_back" name="identity_card_back">
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
                    <label for="attachments" class="form-label">Attachments: Driving license Front</label>
                    <input type="file" class="form-control" id="driver_license" name="driver_license">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Driving license Back</label>
                    <input type="file" class="form-control" id="driver_license_back" name="driver_license_back">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Vehicle insurance Front</label>
                    <input type="file" class="form-control" id="vehicle_insurance_front" name="vehicle_insurance_front">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Vehicle insurance Back</label>
                    <input type="file" class="form-control" id="vehicle_insurance_back" name="vehicle_insurance_back">
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments: Passport</label>
                    <input type="file" class="form-control" id="passport" name="passport">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Save Employee</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection