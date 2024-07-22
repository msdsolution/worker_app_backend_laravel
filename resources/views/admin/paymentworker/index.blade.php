@extends('layouts.master')

@section('title','Payment Management')

@section('content')

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{url('admin/delete-payment')}}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="payment_delete_id" id="payment_id">
          <h5>Are you sure you want to delete this payment?</h5>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Yes Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="container-fluid px-4">
  <div class="card mt-4">
    <div class="card-header">
      <h4>View Worker Payments
        <a href="{{ url('admin/add-paymentworker')}}" class="btn btn-primary btn-sm float-end">Add Payment</a>
      </h4>
    </div>
    <div class="card-body">
      @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
      @endif

      <table id="myDataTable" class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <!-- <th>ID</th> -->
            <th>Job ID</th>
            <th>Amount</th>
            <!-- <th>Status</th> -->
            <!-- <th>Attachment</th> -->
            <th>Download</th>
            <th>View</th>
            <!-- <th>Edit</th>
            <th>Delete</th> -->
          </tr>
        </thead>
        <tbody>
          @foreach($payments as $index => $payment)
            <tr>
              <td>{{ $index + 1 }}</td>
              <!-- <td>{{ $payment->id }}</td> -->
              <td>{{ $payment->job_id }}</td>
              <td>{{ $payment->amount }}</td>
              <!-- <td>{{ $payment->status }}</td> -->
              <!-- <td>{{ $payment->file_path }}</td> -->
              <td>
                @if($payment->file_path)
                    <a href="{{ url('admin/download', $payment->file_path) }}" class="btn btn-success" download>
                        <i class="fas fa-download"></i> Download
                    </a>
                @else
                    No file attached
                @endif
              </td>
              <td>
                @if($payment->file_path)
                    <a href="{{ url('admin/view', $payment->file_path) }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-eye"></i> View
                    </a>
                @else
                    No file attached
                @endif
              </td>
              <!-- <td>
                <a href="{{ url('admin/payment/' . $payment->id) }}" class="btn btn-success">Edit</a>
              </td>
              <td>
                <button type="button" class="btn btn-danger deletePaymentBtn" value="{{ $payment->id }}">Delete</button>
              </td> -->
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
    $(document).on('click', '.deletePaymentBtn', function(e) {
      e.preventDefault();

      var payment_id = $(this).val();
      $('#payment_id').val(payment_id);
      $('#deleteModal').modal('show');
    });
  });
</script>
@endsection
