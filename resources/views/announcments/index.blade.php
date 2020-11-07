@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Announcements</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Announcements</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="row"> 
            @php $search = Request::get('search'); @endphp
            @if(isset($search))
            <div class="cleardiv"> 
            <a href="/admin/annonce" class="btn btn-primary float-right">Clear Search</a>
            </div>
            @endif
            <div class="col-md-12">
              <a href="/admin/announce/add" class="btn btn-primary">Add Announcement</a>
              <form  method="get" style=" float: right;">
                  <div class="input-group mb-3">
                      <input type="text" name="search" class="form-control" value="{{ Request::get('search') }}">
                      <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                      </div>
                    </div>
                </form>
  
                <table class="table table-striped table-bordered table-hover vendorTable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                    <th class="th-sm">Image</th>
                    <th class="th-sm">Title</th>
                    <th class="th-sm">Description</th>
                    <th class="th-sm">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if($users->count() > 0)
                        @foreach($users as $user)
                        <tr>
                        <td class="name"><img style="width: 70px;" src="{{ asset('public/images') }}/{{  $user->image }}"></td>
                        <td class="email">{{$user->title}}</td>
                        <td class="type">{{ str_limit($user->description, 70) }}</td>    
                        <td class="id" style="display: none;">{{$user->id}}</td>    

                        <td>
                             <a type="button" class="btn btn-primary" href="/admin/announce/edit/{{  $user->id }}"><i class="fas fa-edit"></i> Edit</a> 
                             <button type="button" class="btn btn-danger announdelete"><i class="fas fa-trash-alt"></i>Delete</button> 
                        </td>
                          </tr>
                          @endforeach
                        @else
                        <tr><td colspan="4">No Record Found</td></tr>
                        @endif
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>
  </section>
@endsection
