@extends('layouts.master')

@section('title','Company')
@section('content')

<div class="container-fluid px-4">

    <div class="card mt-4">
        <div class="card-header">
        <h4 class=""> Edit employee</h1>
        </div>
        <div class="card-body">

        @if($errors -> any())
        <div class="alert alert-danger">
        @foreach($errors -> all() as $error)
        <div>{{$error}}</div>
        @endforeach
        </div>
        @endif
            <form action="{{url('admin/update-service/' .$Service_Category -> id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label >name</label>
                    <input type="text" name="name" value="{{$Service_Category -> name}}" class="form-control">
                </div>
                
                <div class="mb-3">
                    <label >descritption</label>
                    <!-- <input type="text" name="description" value="{{$Service_Category -> description}}"  class="form-control"> -->

                    <textarea name="description" id="description" class="form-control"
                        rows="4">{{ $Service_Category->description }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Update employee</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection