@extends('layouts.master')

@section('title','Client')
@section('content')

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form action="{{url('admin/delete-client')}}" method="POST">
        @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="client_delete_id" id="client_id">
        <h5>Are you sure You want to delete this Client?</h5>
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
      <h4>View Client 
        <a href="{{ url('admin/add-client')}}" class="btn btn-primary btn-sm float-end">Add client</a>
      </h4>
    </div>
    <div class="card-body">
      @if(session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
      @endif
    <div class="table-responsive">
      <table id="myDataTable" class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Location</th>
            <th>User Address</th>
            <th>Phone Number</th>
            <th>Status</th>
            <th>Edit</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($clients as $index => $item)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{$item->id}}</td>
              <td>{{$item->first_name}}</td>
              <td>{{$item->last_name}}</td>
              <td>{{$item->email}}</td>
              <td>{{$item->location}}</td>
              <td>{{$item->	user_address}}</td>
              <td>{{$item->	phone_no}}</td>
              <td>
              <input 
                  type="checkbox" 
                  role="switch" 
                  class="toggle-class" 
                  data-id="{{ $item->id }}" 
                  data-toggle="toggle" 
                  data-style="slow" 
                  data-on="Verified" 
                  data-off="Not Verified" 
                  {{ $item->is_verified == true ? 'checked' : '' }}
                  @if($item->trashed()) disabled @endif
                >
              </td>
              <td>
                @if($item->trashed())
                  <button class="btn btn-secondary" disabled>Edit</button>
                @else
                  <a href="{{ url('admin/client/' . $item->id ) }}" class="btn btn-success">Edit</a>
                @endif
              </td>
              <td>
                @if($item->trashed())
                  <a href="{{ url('admin/restore-client/' . $item->id) }}" class="btn btn-warning">Restore</a>
                @else
                  <a href="{{ url('admin/delete-client/' . $item->id) }}" class="btn btn-danger">Delete</a>
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
    $(document).ready(function (){
        $(document).on('click', '.deleteCategoryBtn', function(e){
            e.preventDefault();
            var client_id = $(this).val();
            $('#client_id').val(client_id);
            $('#deleteModal').modal('show');
        });
    });
</script>
<script>
  $(function() {
    $('#toggle-two').bootstrapToggle({
      on: 'Verified',
      off: 'Not Verified'
    });
  });
</script>

<script>
  $('.toggle-class').on('change', function() {
    var status = $(this).prop('checked') == true ? 1 : 0;
    var id = $(this).data('id');
    $.ajax({
      type: 'GET',
      dataType: 'JSON',
      url: '{{ route('changeStatusemp') }}',
      data: {
        'status': status,
        'id': id
      },
      success: function(data){
        // Handle success
      }
    });
  });
</script>
@endsection
