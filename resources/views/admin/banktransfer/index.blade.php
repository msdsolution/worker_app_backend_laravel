@extends('layouts.master')

@section('title', 'Worker Feedback Details')

@section('content')

<div class="container-fluid px-4">

    <div class="card mt-4">
        <div class="card-header">
            <h4>View Bank Transfer Details</h4>
        </div>
        <div class="card-body">
            <table id="myDataTable"  class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th> Job Id</th>
                        <th>Amount</th>
                        <th>Attachment</th>
                        <th>Status</th>
                    
                    </tr>
                </thead>
                <tbody>
                    @foreach($Banktransfer as $transfer)
                        <tr>
                            <td>{{ $transfer->id }}</td>
                            <td>{{ $transfer->job_id }}</td>
                            <td>{{ $transfer->amount }}</td>
                            <td>{{ $transfer->attachment_url }}</td>
                            <td> <input type="checkbox" role="switch" class="toggle-class" data-id="{{ $transfer->id }}" data-toggle="toggle" data-style="slow" data-on="Approved" data-off="Un Approved" {{ $transfer->status == true ? 'checked' : ''}}></td>
                            
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
            url: '{{ route('changeStatustransfer') }}',
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
