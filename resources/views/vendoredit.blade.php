@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Clients</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/home">Home</a></li>
            <li class="breadcrumb-item active">Clients</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
      <div class="row"> 
        <div class="col-md-3"></div>

          <div class="col-md-6">
            <form  method="post" action="/admin/vendor/update" enctype="multipart/form-data">
                @csrf 
            <input type="hidden" name="id" value="{{ $user->id }}"> 
                  <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                <input type="text" name="email" class="form-control" value="{{ $user->email }}">
                  </div>
                  <div class="form-group">
                    <label>Collection Id</label>
                    <input type="text" name="type" class="form-control" value="{{ $user->type }}">
                  </div>
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                  </div>
                  <div class="form-group">
                    <label>Profit</label>
                    <input type="text" name="profit" class="form-control" value="{{ $user->profit }}">
                  </div>
                  <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control" value="{{ $user->image }}">
                  </div>
                  @if($user->image)
                  <img class="dumyimg" src="{{ asset('public/images') }}/{{  $user->image }}" width="100px" />
                  @else
                  <img class="dumyimg" src="https://via.placeholder.com/150"  width="100px" />

                 @endif
                  <div class="form-group">
                    <input type="submit"  class="btn btn-success" style="float: right;"> 
                  </div>
              </form>
          </div>
      </div>
      </div>
@endsection