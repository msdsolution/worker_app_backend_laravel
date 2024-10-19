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

                <div class="mb-3">
                    <label>User Address</label>
                    <input type="text" name="user_address" value="{{ $employee->user_address }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Phone Number</label>
                    <input type="text" name="phone_no" value="{{ $employee->phone_no }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label>User Description</label>
                    <input type="text" name="description" value="{{ $employee->description }}" class="form-control">
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
                        @case(1) Identity Card Front @break
                            @case(2) Police Clearance Certificate @break
                            @case(3) Gramasewaka Certificate @break
                            @case(4) Driving License @break
                            @case(5) Vehicle Insurance Front @break
                            @case(6) Passport @break
                            @case(7) Identity Card Back  @break
                            @case(8) Driving License back  @break
                            @case(9)  Vehicle Insurance Back @break
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
                                <div>
                                    <a href="{{ $docUrl }}" target="_blank">View Full Image</a>
                                </div>

                            </div>
                        @endif
                        <button type="button" class="btn btn-danger btn-sm mt-2 delete-document" data-id="{{ $document->id }}">Delete</button>
                    @endif
                    
                </div>
                @endforeach

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

@section('scripts')
<script>
    $(document).on('click', '.delete-document', function() {
        var docId = $(this).data('id');
        var token = '{{ csrf_token() }}';

        if (confirm('Are you sure you want to delete this document?')) {
            $.ajax({
                url: 'delete-document/' + docId,
                type: 'DELETE',
                data: {
                    "_token": token,
                },
                success: function(response) {
                    if (response.success) {
                        alert('Document deleted successfully.');
                        location.reload(); // Reload the page or remove the document section dynamically
                    } else {
                        alert('Failed to delete the document.');
                    }
                },
                error: function(response) {
                    alert('Error occurred while deleting the document.');
                }
            });
        }
    });
</script>

@endsection