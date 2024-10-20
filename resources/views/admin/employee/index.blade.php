@extends('layouts.master')

@section('title', 'Employee')

@section('content')

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{url('admin/delete-employee/{employee_id}')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="employee_delete_id" id="employee_id">
                    <h5>Are you sure you want to delete this employee?</h5>
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
            <h4>View Employee 
                @if(auth()->user()->user_type != 4) 
                    <a href="{{ url('admin/add-employee')}}" class="btn btn-primary btn-sm float-end">Add Employee</a>
                @endif
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
                            <th>Edit</th>
                            @if(auth()->user()->user_type != 4) 
                                <th>Status</th>
                                <th>Delete/Restore</th>
                            @endif

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $index => $item)      
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{$item->id}}</td>
                                <td>{{$item->first_name}}</td>
                                <td>{{$item->last_name}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->location}}</td>
                                <td>{{$item->user_address}}</td>
                                <td>{{$item->phone_no}}</td>
                                <td>
                                    @if($item->trashed())
                                        <button class="btn btn-success" disabled>Edit</button>
                                    @else
                                        <a href="{{url('admin/edit-employee/' . $item->id )}}" class="btn btn-success">Edit</a>
                                    @endif
                                </td>
                                @if(auth()->user()->user_type != 4) 
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
                                            <a href="{{ url('admin/restore-employee/' . $item->id) }}" class="btn btn-warning">Restore</a>
                                        @else
                                            <a href="{{url('admin/delete-employee/' . $item->id )}}" class="btn btn-danger">Delete</a>
                                        @endif
                                    </td>
                                @endif
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
            var employee_id = $(this).val();
            $('#employee_id').val(employee_id);
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
