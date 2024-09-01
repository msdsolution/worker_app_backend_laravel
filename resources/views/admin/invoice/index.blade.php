@extends('layouts.master')

@section('title', 'Invoice Details')

@section('content')

<!-- Delete Modal -->
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

<!-- Send Modal -->
<div class="modal fade" id="sendModal" tabindex="-1" aria-labelledby="sendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendModalLabel">Send Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendForm" action="{{ url('admin/send-invoice') }}" method="POST">
                @csrf
                <input type="hidden" name="jobId" id="modalJobId">
                <div class="modal-body">
                    <!-- Client Details -->
                    <div class="mb-3">
                        <label for="clientName" class="form-label">Client Name</label>
                        <input type="text" class="form-control" id="clientName" name="clientName" required>
                    </div>
                    <div class="mb-3">
                        <label for="clientEmail" class="form-label">Client Email</label>
                        <input type="email" class="form-control" id="clientEmail" name="clientEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <!-- PDF Preview -->
                    <div class="mb-3">
                        <label for="pdfPreview" class="form-label">PDF Preview</label>
                        <iframe id="pdfPreview" src="" style="width: 100%; height: 400px;" frameborder="0"></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid px-4">
    <div class="card mt-4">
        <div class="card-header">
            <h4>Invoice Details</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="myDataTable"  class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Job No</th>
                            <th>Referer Name</th>
                            <th>Service Name</th>
                            <th>Service Description</th>
                            <th>Worker name</th>
                            <th>Status</th>
                            <th>Download</th>
                            <th>View</th>
                            <th>Send</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($InvDetails as $index => $job)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $job->job_no }}</td>
                                <td>{{ $job->userFirstName }}</td>
                                <td>{{ $job->serviceName }}</td>
                                <td>{{ $job->serviceDescription }}</td>
                                <td>
                                    @if($job->worker_id)
                                        {{ $job->workerName }}
                                    @else
                                        No worker assigned
                                    @endif
                                </td>
                                <td class="{{ $job->status == 4 ? 'text-danger' : '' }}">
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
                                    <a href="{{ url('admin/download-pdf', ['jobId' => $job->jobId]) }}" class="btn btn-success">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('view-pdf', ['jobId' => $job->jobId]) }}" class="btn btn-info" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-info sendInvoiceBtn" data-job-id="{{ $job->jobId }}" data-client-name="{{ $job->userFirstName }} {{ $job->userLastName }}" data-client-email="{{ $job->Email }}">
                                        <i class="fas fa-envelope"></i> Send
                                    </a>
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
        // Handle click event for Send button
        $(document).on('click', '.sendInvoiceBtn', function (e) {
            e.preventDefault();
            
            var jobId = $(this).data('job-id');
            var clientName = $(this).data('client-name');
            var clientEmail = $(this).data('client-email');
            
            // Set the hidden input value for jobId
            $('#modalJobId').val(jobId);

            // Set the client name and email fields
            $('#clientName').val(clientName);
            $('#clientEmail').val(clientEmail);

            // Set PDF preview URL
            $('#pdfPreview').attr('src', '{{ url("admin/view-pdf") }}/' + jobId);

            $('#sendModal').modal('show');
        });
    });
</script>
@endsection
