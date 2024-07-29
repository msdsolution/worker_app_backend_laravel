@extends('layouts.master')

@section('title', 'Job Service Details')

@section('content')

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('admin/delete-employee/{employee_id}') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="company_delete_id" id="employee_id">
                    <h5>Are you sure you want to delete this Employee?</h5>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid px-4">
    <div class="card mt-4">
        <div class="card-header">
            <h4>View Job Service Details</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table  id="myDataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Job Number</th>
                            <th>Referer Name</th>
                            <th>Service Name</th>
                            <th>Service Description</th>
                            <th>Required Date</th>
                            <th>Required Time</th>
                            <th>Preferred Sex</th>
                            <th>City name</th>
                            <th>Worker name</th>
                            <th>Status</th>
                            <th>Assign</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobDetails as $index => $job)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $job->job_no }}</td>
                                <td>{{ $job->userFirstName }}</td>
                                <td>{{ $job->serviceName }}</td>
                                <td>{{ $job->serviceDescription }}</td>
                                <td>{{ $job->required_date }}</td>
                                <td>{{ $job->required_time }}</td>
                                <td>{{ $job->preferred_sex }}</td>
                                <td>{{ $job->cityName }}</td>
                                <td>
                                    @if($job->worker_id && $job->status == 1)
                                        {{ $job->workerName }}
                                    @elseif(!$job->worker_id && $job->status == 0)
                                        No worker assigned
                                    @elseif($job->worker_id && in_array($job->status, [2, 3, 4,5]))
                                        {{ $job->workerName }}
                                    @elseif($job->worker_id && $job->status == 6)
                                        Worker Rejected
                                    @endif
                                </td>
                                <td>
                                    @if($job->status == 0)
                                        Pending
                                    @elseif($job->status == 1)
                                        Assigned
                                    @elseif($job->status == 2)
                                        Worker accepted
                                    @elseif($job->status == 3)
                                        Worker started
                                    @elseif($job->status == 4)
                                        Worker finished
                                    @elseif($job->status == 5)
                                          Paid
                                    @elseif($job->status == 6)
                                        Worker Rejected
                                  
                                    @endif
                                </td>
                                <td>
                                    @if ($job->worker_id === null && $job->status === 0)
                                        <a href="{{ route('assign-job', $job->jobId) }}" class="btn btn-primary">Assign</a>
                                    @elseif ($job->worker_id !== null && $job->status === 1)
                                        <a href="{{ route('assign-job', $job->jobId) }}" class="btn btn-success">Assigned</a>
                                    @elseif ($job->worker_id !== null && $job->status === 2)
                                        <a href="{{ route('assign-job', $job->jobId) }}" class="btn btn-info">Worker Accepted</a>
                                    @elseif ($job->worker_id !== null && $job->status === 3)
                                        <a href="{{ route('assign-job', $job->jobId) }}" class="btn btn-warning">Worker Started</a>
                                    @elseif ($job->worker_id !== null && $job->status === 4)
                                        <a href="{{ route('assign-job', $job->jobId) }}" class="btn btn-success">Worker Finished</a>
                                    @elseif ($job->worker_id !== null && $job->status === 5)
                                        <a href="{{ route('assign-job', $job->jobId) }}" class="btn btn-outline-success">Paid</a>
                                    @elseif ($job->worker_id !== null && $job->status === 6)
                                        <a href="{{ route('assign-job', $job->jobId) }}" class="btn btn-danger">Worker Rejected</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $(document).on('click', '.deleteCategoryBtn', function (e) {
            e.preventDefault();
            var employee_id = $(this).val();
            $('#employee_id').val(employee_id);
            $('#deleteModal').modal('show');
        });
    });
</script>
@endsection
