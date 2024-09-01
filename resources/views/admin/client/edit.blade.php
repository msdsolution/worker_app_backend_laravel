@extends('layouts.master')

@section('title', 'Company')
@section('content')

<div class="container-fluid px-4">

    <div class="card mt-4">
        <div class="card-header">
            <h4>Edit Client</h4>
        </div>
        <div class="card-body">

            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <form action="{{ url('admin/update-client/' . $client->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="{{ $client->first_name }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="{{ $client->last_name }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="text" name="email" value="{{ $client->email }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" value="{{$client -> password}}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Location</label>
                    <input type="text" name="location" value="{{ $client->location }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>User Address</label>
                    <input type="text" name="user_address" value="{{ $client->user_address }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Phone Number</label>
                    <input type="text" name="phone_no" value="{{ $client->phone_no }}" class="form-control">
                </div>

                @foreach($documentMap as $fieldName => $docId)
                @php
                    $document = $documents->firstWhere('doc_id', $docId);
                    $docUrl = $document ? asset('storage/' . $document->doc_url) : null;
                    $isPdf = $document && strtolower(pathinfo($document->doc_url, PATHINFO_EXTENSION)) === 'pdf';
                @endphp
                <div class="mb-3">
                    <label>
                        @switch($docId)
                            @case(1) Identity Card @break
                            @case(2) Police Clearance Certificate @break
                            @case(3) Gramasewaka Certificate @break
                            @case(4) Driving License @break
                            @case(5) Vehicle Insurance @break
                            @case(6) Passport @break
                        @endswitch
                    </label>
                    <input type="file" class="form-control" name="{{ $fieldName }}">
                    @if($docUrl)
                        @if($isPdf)
                            <div class="mt-2">
                                <a href="{{ $docUrl }}" target="_blank">View Current PDF</a>
                            </div>
                        @else
                            <div class="mt-2">
                                <img src="{{ $docUrl }}" alt="Current File" style="max-width: 200px; max-height: 200px;">
                            </div>
                        @endif
                    @endif
                </div>
                @endforeach

                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Update Client</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
