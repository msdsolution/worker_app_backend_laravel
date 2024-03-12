@extends('layouts.master')

@section('title', 'Worker Feedback Details')

@section('content')

<div class="container-fluid px-4">

    <div class="card mt-4">
        <div class="card-header">
            <h4>View Worker Feedback Details</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Job Description</th>
                        <th>Worker name</th>
                        <th>Message</th>
                        <th>Rating</th>
                        <th>Show in App</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workerFeedback as $feedback)
                        <tr>
                            <td>{{ $feedback->id }}</td>
                            <td>{{ $feedback->job_description }}</td>
                            <td>{{ $feedback->user_name }}</td>
                            <td>{{ $feedback->message }}</td>
                            <td>{{ $feedback->ratings }}</td>
                            <td> <input type="checkbox" role="switch" class="toggle-class" data-id="{{ $feedback->id }}" data-toggle="toggle" data-style="slow" data-on="Show" data-off="Hide" {{ $feedback->status == true ? 'checked' : ''}}></td>
                                <!-- <a href="{{ url('medicine-delete') }}/{{ $feedback->id }}" class="btn btn-danger"><i
                                    class="fa fa-trash-o" aria-hidden="true"></i>
                                Delete</a> -->
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
  $(function() {
    $('#toggle-two').bootstrapToggle({
      on: 'Show',
      off: 'Hide'
    });
    
  })
</script>
<script>
    $('.toggle-class').on('change', function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');
       $.ajax({
        type: 'GET',
            dataType: 'JSON',
            url: '{{ route('changeStatus') }}',
            data: {
                'status': status,
                'id': id
            },
            success:function(data){

            }
       })
    });
</script>
@endsection
