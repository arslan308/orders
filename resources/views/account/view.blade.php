@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Account</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/home">Home</a></li>
            <li class="breadcrumb-item active">Account</li>
          </ol>
        </div>
      </div>

    </div>
  </div>

  <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
          <form method="post" action="/admin/account/update2" enctype="multipart/form-data" autocomplete="off">
                @csrf 
              <div class="form-group">
                  <label>Email</label>
                  <input class="form-control" name="email" type="email" value="{{ $account['email'] }}"> 
              </div>
              <div class="form-group">
                <label>Password</label>
                <input class="form-control" name="password" type="password" autocomplete="off">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input class="form-control" name="phone" type="text"  value="{{ $account['phone'] }}">
            </div>
            <div class="form-group">
                <label>Image</label>
                <input class="form-control" name="image" type="file" >
                @if($account['image'])
                <img class="dumyimg" src="{{ asset('public/images') }}/{{  $account['image'] }}"  width="100px" />
                @else
                <img class="dumyimg" src="https://via.placeholder.com/150"  width="100px" />

               @endif
            </div>
            <div class="form-group" style="float: right;">
                <input class="form-control btn btn-primary" name="submit" type="submit"  value="Update"  style="float: right;">
            </div>
          </form>
          </div>
      </div>
      </div>
@endsection