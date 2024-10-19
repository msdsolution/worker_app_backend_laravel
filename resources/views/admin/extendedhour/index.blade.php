@extends('layouts.master')

@section('title','Company')
@section('content')

<div class="container-fluid px-4">
    <div class="card mt-4">
        <div class="card-header">
            <h4>{{ $type === 'worker' ? 'Extended Hour Rates (Worker)' : 'Extended Hour Rates (Client)' }}</h4>
        </div>
        <div class="card-body">
            @if(session('message'))
                <div class="alert alert-success">{{session('message')}}</div>
            @endif

            <table id="myDataTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Extended Hours</th>
                        <th>Amount</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exted_hr as $item)
                    <tr>
                        <td>{{ $item->hour_extended ?? $item->hr_extended }}</td>
                        <td>{{ $item->amount }}</td>
                        <td>

                        @if($type == 'worker')
    <a href="{{ url('admin/edit-extendex-hour/worker/' . $item->id) }}" class="btn btn-success">Edit</a>
@elseif($type == 'client')
    <a href="{{ url('admin/edit-extendex-hour/client/' . $item->id) }}" class="btn btn-success">Edit</a>
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
