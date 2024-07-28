@extends('layouts.master')

@section('title', 'Add Payment')

@section('content')
<div class="container-fluid px-4">
    <div class="card mt-4">
        <div class="card-header">
            <h4> Add worker payment </h4>
        </div>
        <div class="card-body">
            @if(session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('store-payment') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Other form fields can go here -->

                <!-- Worker Dropdown -->
                <div class="mb-3">
                    <label for="worker" class="form-label">Select Worker</label>
                    <select class="form-select" id="worker" name="worker_id">
                        <option value="">Select Worker</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}">
                                {{ $worker->first_name }} {{ $worker->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jobs Checkbox Group -->
                <div class="mb-3">
                    <label for="jobs" class="form-label"><strong>Select Jobs:</strong></label><br/>
                    <div id="jobs-checkboxes">
                        <!-- Checkbox options will be populated dynamically using JavaScript -->
                    </div>
                </div>

                <!-- Referral Amount -->
                <div class="mb-3">
                    <label for="referral_amount" class="form-label"><strong>Total Job Amount.:</strong></label>
                    <input type="text" class="form-control" id="referral_amount" name="referral_amount" readonly>
                </div>

                <!-- Paid Amount -->
                <div class="mb-3">
                    <label for="paid_amount" class="form-label"><strong>Paying Amount:</strong></label>
                    <input type="text" class="form-control" id="paid_amount" name="paid_amount">
                </div>

                <!-- File Upload -->
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments:</label>
                    <input type="file" class="form-control" id="attachments" name="attachments[]">
                </div>

                <!-- Validation Message -->
                <div id="validation_message"></div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Add Payment</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#worker').change(function() {
            var workerId = $(this).val();
            
            // Clear existing checkboxes and message
            $('#jobs-checkboxes').empty();
            $('#referral_amount').val('');
            $('#paid_amount').val('');
            $('#validation_message').empty();

            // Fetch jobs for the selected worker via AJAX
            $.ajax({
                url: '{{ url('admin/get-worker-jobs') }}/' + workerId,
                type: 'GET',
                success: function(response) {
                    var jobs = response.jobs;

                    if (jobs.length > 0) {
                        // Populate jobs as checkboxes with job numbers only
                        $.each(jobs, function(index, job) {
                            $('#jobs-checkboxes').append(`
                                <div class="form-check">
                                    <input class="form-check-input job-checkbox" type="checkbox" value="${job.id}" id="job${job.id}" name="jobs[]">
                                    <label class="form-check-label" for="job${job.id}">
                                        Job No: ${job.job_no}
                                    </label>
                                </div>
                            `);
                        });
                    } else {
                        // Display a message if there are no jobs
                        $('#jobs-checkboxes').append(`
                            <p class="text-muted">No jobs available for this worker currently.</p>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching jobs: ' + error);
                    $('#jobs-checkboxes').append(`
                        <p class="text-danger">Error fetching jobs. Please try again later.</p>
                    `);
                }
            });
        });

        // Handle checkbox change
        $(document).on('change', '.job-checkbox', function() {
            var checkedJobs = $('.job-checkbox:checked');
            var totalAmount = 0;

            checkedJobs.each(function(index, checkbox) {
                var jobId = $(checkbox).val();
                
                // Fetch amount for the selected job via AJAX
                $.ajax({
                    url: '{{ url('admin/get-referral-amount') }}/' + jobId,
                    type: 'GET',
                    success: function(response) {
                        totalAmount += parseFloat(response.referral_amount);

                        // Update referral amount field
                        $('#referral_amount').val(totalAmount.toFixed(2));
                        validatePaidAmount();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching referral amount: ' + error);
                        // Remove amount of unchecked jobs if error occurs
                        totalAmount -= parseFloat(response.referral_amount);
                        $('#referral_amount').val(totalAmount.toFixed(2));
                    }
                });
            });
        });

        // Validate paid amount while typing
        $('#paid_amount').on('input', function() {
            validatePaidAmount();
        });

        function validatePaidAmount() {
            var paidAmount = parseFloat($('#paid_amount').val());
            var referralAmount = parseFloat($('#referral_amount').val());

            if (isNaN(paidAmount)) {
                paidAmount = 0;
            }

            if (paidAmount !== referralAmount) {
                // Display error message
                $('#validation_message').html('<div class="alert alert-danger">Paid amount does not match referral amount.</div>');
            } else {
                // Clear error message
                $('#validation_message').empty();
            }
        }

        // Validate single file selection
        $('#attachments').change(function() {
            if (this.files.length > 1) {
                alert('You can only upload one file.');
                this.value = ''; // Clear the selected files
            }
        });
    });
</script>
@endsection
