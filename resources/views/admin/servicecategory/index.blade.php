@extends('layouts.master')

@section('title','Service Categories')
@section('content')

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ url('admin/delete-service/{service_id}') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Service Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="service_delete_id" id="service_id">
          <h5>Are you sure you want to delete this service category?</h5>
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
      <h4>View Service Categories
        <a href="{{ url('admin/add-service') }}" class="btn btn-primary btn-sm float-end">Add New Service Category</a>
      </h4>
    </div>
    <div class="card-body">
      @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
      @endif

      <table id="myDataTable" class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Edit</th>
            <th>Delete/Restore</th>
          </tr>
        </thead>
        <tbody>
          @foreach($Service_Category as $item)
            <tr>
              <td>{{ $item->id }}</td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->description }}</td>
              <td>
                @if($item->trashed())
                  <button class="btn btn-success" disabled>Edit</button>
                @else
                  <a href="{{ url('admin/edit-service/' . $item->id) }}" class="btn btn-success">Edit</a>
                @endif
              </td>
              <td>
                @if($item->trashed())
                  <a href="{{ url('admin/restore-service/' . $item->id) }}" class="btn btn-warning">Restore</a>
                @else
                  <a href="{{ url('admin/delete-service/' . $item->id) }}" class="btn btn-danger">Delete</a>
                @endif
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
  $(document).ready(function () {
    $(document).on('click', '.deleteCategoryBtn', function(e) {
      e.preventDefault();
      var service_id = $(this).val();
      $('#service_id').val(service_id);
      $('#deleteModal').modal('show');
    });
  });
</script>
@endsection
