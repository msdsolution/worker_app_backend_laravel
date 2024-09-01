@extends('layouts.master')

@section('title', 'Jobs with Complaints')

@section('content')
<div class="container mt-4">
    <h1>Jobs with Complaints</h1>

    <div class="row">
        <!-- Job List -->
        <div class="col-md-12">
            <table id="myDataTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Job Number</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                        <tr>
                            <td><a href="{{ route('get-chat-messages', $job->id) }}">Job Number {{ $job->job_no }}</a></td>
                            <td>
                                <input type="checkbox" role="switch" class="toggle-class" data-id="{{ $job->id }}" data-toggle="toggle" data-style="slow" data-on="Resolved" data-off="Unsolved" {{ $job->complaint_status == 3 ? 'checked' : '' }}>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')


<script>
$(document).ready(function() {
  

    // Handle the toggle switch change event
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') ? 3 : 1; // 3 for resolved, 1 for unsolved
        var jobId = $(this).data('id');
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url: '/admin/update-complaint-status',
            data: {
                '_token': '{{ csrf_token() }}',
                'job_id': jobId,
                'complaint_status': status
            },
            success: function(data) {
                console.log('Status updated successfully');
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    });
});
</script>
@endsection
