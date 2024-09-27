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
                    <label for="RefererFirstName" class="form-label">Referer First Name</label>
                    <input type="text" name="RefererFirstName" value="{{ $job->userFirstName }}" class="form-control" id="RefererFirstName" readonly>
                </div>
                <div class="mb-3">
                    <label for="RefererLastName" class="form-label">Referer Last Name</label>
                    <input type="text" name="RefererLastName" value="{{ $job->userLasttName }}" class="form-control" id="RefererLastName" readonly>
                </div>
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
                    <label for="Jobcreateddate" class="form-label">Job Created Date</label>
                    <textarea name="job_createdat" class="form-control" id="job_createdat" rows="3" readonly>{{ $job->created_at }}</textarea>
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
                    <input type="text" name="cityName" value="{{ $job->cityName }} ({{ $district->districtName }})" class="form-control" id="cityName" readonly>
                </div>

                <div class="mb-3">
                    <label for="startLocation" class="form-label">Start Location</label>
                    <input type="text" name="startLocation" value="{{ $job->start_location }}" class="form-control" id="startLocation" readonly>
                </div>

                <div class="mb-3">
                    <label for="endLocation" class="form-label">End Location</label>
                    <input type="text" name="endLocation" value="{{ $job->end_location }}" class="form-control" id="endLocation" readonly>
                </div>

                <!-- <div class="mb-3">
                    <label for="district" class="form-label">Select Area</label>
                    <select name="district" class="form-control" id="district_id" onchange="updateWorkers()"@if(in_array($job->status, [1, 2, 3, 4, 5])) disabled @endif>
                        <option value="">Select District</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name_en }}</option>
                        @endforeach
                    </select>
                </div> -->

                
                <!-- <div class="mb-3">
    <label for="district" class="form-label">Select Area</label>
    <select name="district" class="form-control" id="district_id" onchange="updateWorkers()" @if(in_array($job->status, [1, 2, 3, 4, 5])) disabled @endif>
        <option value="">Select District</option>
        @foreach($districts as $district)
            <option value="{{ $district->id }}" @if($district->id == $job->worker_area_id) selected @endif>
                {{ $district->name_en }}
            </option>
        @endforeach
    </select>
</div> -->



<div class="mb-3">
    <label for="district" class="form-label">Select Worker Area</label>
    <select name="district" class="form-control" id="district_id" onchange="updateWorkers()"
    @if(!in_array($job->status, [0, 6])) disabled @endif>
        <option value="">Select District</option>
        @foreach($districts as $district)
            <option value="{{ $district->id }}"
                @if($job->status !== 6 && $district->id == $job->worker_area_id) selected @endif>
                {{ $district->name_en }}
            </option>
        @endforeach
    </select>
</div>




    <!-- Space here--- -->
                <!-- <div class="mb-3">
    <label for="workerName" class="form-label">Worker Full Name (First Name and Last Name)</label>
    <select name="workerId" class="form-control" id="workerId" onchange="updateSelectedWorkerId()" @if(in_array($job->status, [1, 2, 3, 4, 5])) disabled @endif>
        <option value="">Select Worker</option> -->
        <!-- Options will be added here via AJAX -->
        <!-- @if($job->worker_id) -->
            <!-- Display the assigned worker if a worker is assigned -->
            <!-- @foreach($workers as $worker)
                @if($job->worker_id == $worker->id) -->
                    <!-- <option value="{{ $worker->id }}" selected>{{ $worker->first_name }} {{ $worker->last_name }}</option> -->
                    <!-- <option value="{{ $worker->id }}" @if($job->status != 6 && $job->worker_id == $worker->id) selected @endif>{{ $worker->first_name }} {{ $worker->last_name }}</option>
                @endif
            @endforeach
        @endif
    </select>
</div> -->



<!-- Space here ---- -->
<div class="mb-3">
    <label for="workerName" class="form-label">Worker Full Name (First Name and Last Name)</label>
    <select name="workerId" class="form-control" id="workerId" onchange="updateSelectedWorkerId()" @if(in_array($job->status, [1, 2, 3, 4, 5])) disabled @endif>
        <option value="">Select Worker</option>
        @if($job->status != 6 && $job->worker_id)
            <!-- Display the assigned worker if a worker is assigned and job status is not 6 -->
            @foreach($workers as $worker)
                @if($job->worker_id == $worker->id)
                    <option value="{{ $worker->id }}" selected>{{ $worker->first_name }} {{ $worker->last_name }}</option>
                @endif
            @endforeach
        @endif
        <!-- Options will be added here via AJAX -->
    </select>
</div>


<!-- <div class="mb-3">
    <label for="workerName" class="form-label">Worker Full Name (First Name and Last Name)</label>
    <select name="workerId" class="form-control" id="workerId" onchange="updateSelectedWorkerId()" @if(in_array($job->status, [1, 2, 3, 4, 5])) disabled @endif>
                        <option value="">Select Worker</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}" @if($job->status != 6 && $job->worker_id == $worker->id) selected @endif>{{ $worker->first_name }} {{ $worker->last_name }}</option>
                        @endforeach
                    </select>

</div> -->
                
                <input type="hidden" name="selectedWorkerId" id="selectedWorkerId">

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" name="status" value="{{ getStatusText($job->status) }}" class="form-control" id="status" readonly>
                </div>
                @if ($job->status == 4 || $job->status == 5)
                    <div class="mb-3">
                        <label for="finishJobDescription" class="form-label">Finish Job Description by Worker</label>
                        <input type="text" name="finishJobDescription" value="{{ $job->finishJobDescription }}" class="form-control" id="finishJobDescription" readonly>
                    </div>
                    <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments</label>
                    <div>
                        @foreach($attachments as $attachment)
                            <img src="{{ asset('storage/' . $attachment->img_url) }}" alt="Attachment" width="200">
                        @endforeach
                    </div>
                </div>
                @endif
           

                <div class="row">
                    <div class="col-md-6">
                        @if ($job->status === 0)
                            <button type="submit" class="btn btn-primary">Assign</button>
                        @elseif ($job->status === 6)
                            <button type="submit" class="btn btn-primary">Reassign</button>
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
    function updateWorkers() {
        var districtId = document.getElementById('district_id').value;
        var workerDropdown = document.getElementById('workerId');
       
        // Clear existing options
        workerDropdown.innerHTML = '<option value="">Select Worker</option>';
        if (districtId) {
            // Fetch workers for the selected district via AJAX
            fetch('get-workers-by-district/' + districtId)
                .then(response => response.json())
                .then(data => {
                    data.workers.forEach(worker => {
                        var option = document.createElement('option');
                        option.value = worker.id;
                        option.text = worker.first_name + ' ' + worker.last_name;
                        workerDropdown.add(option);
                    });
                })
                .catch(error => console.error('Error fetching workers:', error));
        }
    }
</script>
@endsection


