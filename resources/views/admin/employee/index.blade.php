@extends('layouts.master')

@section('title','Company')
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
        <input type="hidden" name="company_delete_id" id="employee_id">
        <h5>Are you sure You want to delete this Employee?</h5>
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
    <a href="{{ url('admin/add-employee')}}" class="btn btn-primary btn-sm float-end">Add Employee</a>
    </h4>
    </div>
    <div class="card-body">
    @if(session('message'))
    <div class="alert alert-success">{{session('message')}}</div>
    @endif

    <table id="myDataTable" class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Location</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $item)

      
        <tr>
            <td>{{$item -> id}}</td>
            <td>{{$item -> first_name}}</td>
            <td>{{$item -> last_name}}</td>
            <td>{{$item -> email}}</td>
            <td>{{$item -> location}}</td>
            <td>
                <a href="{{url('admin/edit-employee/' .$item -> id )}}" class="btn btn-success">Edit</a>
            </td>
            <td>
            <a href="{{url('admin/delete-employee/' .$item -> id )}}" class="btn btn-danger">Delete</a>
            <!-- <button type="button" class="btn btn-danger deleteCategoryBtn" value="{{$item -> id}}">Delete</button> -->
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
    $(document).ready(function (){
       // $('.deleteCategoryBtn').click(function(e){

            $(document).on('click', '.deleteCategoryBtn',function(e){
        
           // });
            e.preventDefault();

          var employee_id =  $(this).val();
          $('#employee_id').val(employee_id);
          $('#deleteModal').modal('show');
        });
    });
</script>
@endsection