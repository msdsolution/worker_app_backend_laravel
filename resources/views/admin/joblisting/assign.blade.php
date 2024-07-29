@extends('layouts.master')

@section('title', 'Edit Job Service')

@section('content')

<div class="container-fluid px-4">

    <div class="card mt-4">
        <div class="card-header">
            <h4>Edit Job Service</h4>
        </div>
        <div class="card-body">

            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <form action="{{ route('assigning-job', ['jobId' => $job->jobId]) }}"  method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="serviceName" class="form-label">Service Name</label>
                    <input type="text" name="serviceName" value="{{ $job->serviceName }}" class="form-control" id="serviceName" readonly>
                </div>

                <div class="mb-3">
                    <label for="serviceDescription" class="form-label">Service Description</label>
                    <textarea name="serviceDescription" class="form-control" id="serviceDescription" rows="3" readonly>{{ $job->serviceDescription }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="Jobdescription" class="form-label">Job Description</label>
                    <textarea name="Jobdescription" class="form-control" id="Jobdescription" rows="3" readonly>{{ $job->jobDescription }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="requiredDate" class="form-label">Required Date</label>
                    <input type="text" name="requiredDate" value="{{ $job->required_date }}" class="form-control" id="requiredDate" readonly>
                </div>

                <div class="mb-3">
                    <label for="requiredTime" class="form-label">Required Time</label>
                    <input type="text" name="requiredTime" value="{{ $job->required_time }}" class="form-control" id="requiredTime" readonly>
                </div>

                <div class="mb-3">
                    <label for="preferredSex" class="form-label">Preferred Sex</label>
                    <input type="text" name="preferredSex" value="{{ $job->preferred_sex }}" class="form-control" id="preferredSex" readonly>
                </div>

                <div class="mb-3">
                    <label for="cityName" class="form-label">City Name</label>
                    <input type="text" name="cityName" value="{{ $job->cityName }}" class="form-control" id="cityName" readonly>
                </div>

                <div class="mb-3">
                    <label for="startLocation" class="form-label">Start Location</label>
                    <input type="text" name="startLocation" value="{{ $job->start_location }}" class="form-control" id="startLocation" readonly>
                </div>

                <div class="mb-3">
                    <label for="endLocation" class="form-label">End Location</label>
                    <input type="text" name="endLocation" value="{{ $job->end_location }}" class="form-control" id="endLocation" readonly>
                </div>
                <div class="mb-3">
                    <label for="workerName" class="form-label">Worker Name</label>
                    <!-- <select name="workerId" class="form-control" id="workerId"  onchange="updateSelectedWorkerId()"> -->
                    <select name="workerId" class="form-control" id="workerId" onchange="updateSelectedWorkerId()" @if(in_array($job->status, [1, 2, 3, 4,5])) disabled @endif>
        <option value="">Select Worker</option>
        @foreach($workers as $worker)
            <!-- <option value="{{ $worker->id }}">{{ $worker->first_name }}</option> -->
            <option value="{{ $worker->id }}" @if($job->worker_id == $worker->id) selected @endif>{{ $worker->first_name }}</option>
        @endforeach
    </select>
                </div>
                <input type="hidden" name="selectedWorkerId" id="selectedWorkerId">
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" name="status" value="{{ getStatusText($job->status) }}" class="form-control" id="status" readonly>
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments</label>
                    <div>
                        @foreach($attachments as $attachment)
                            <img src="{{ asset('storage/' . $attachment->img_url) }}" alt="Attachment" width="200">
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                    <!-- <button type="submit" class="btn btn-primary">Edit</button> -->
                    <!-- <button type="submit" class="btn btn-primary" @if(in_array($job->status, [1, 2, 3, 4])) disabled @endif>Edit</button> -->
                    @if ($job->status === 0)
                            <button type="submit" class="btn btn-primary">Assign</button>
                        @elseif ($job->status === 6)
                            <button type="submit" class="btn btn-primary">Assign back</button>
                        @else
                            <button type="submit" class="btn btn-primary" disabled>Edit</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@php
    function getStatusText($status) {
        switch ($status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Assigned';
            case 2:
                return 'Worker accepted';
            case 3:
                return 'Worker started';
            case 4:
                return 'Worker finished';
             case 5:
                    return 'Paid';
             case 6:
                    return 'Worker Rejected';
            default:
                return 'Unknown';
        }
    }
@endphp
@section('scripts')
<script>
    function updateSelectedWorkerId() {
        var selectedWorkerId = document.getElementById('workerId').value;
        document.getElementById('selectedWorkerId').value = selectedWorkerId;
        console.log(selectedWorkerId);
    }
    function validateForm() {
        var workerId = document.getElementById('workerId').value;
        if (workerId === "") {
            alert("Please select a worker.");
            return false;
        }
        return true;
    }
</script>
@endsection