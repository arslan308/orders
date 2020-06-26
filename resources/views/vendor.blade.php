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
      <div class="row mb-2">
          <div class="col-md-12 text-right">
              <button class="btn btn-success multi-add-to-cart" style="opacity:0;">Add to cart</button>
          </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
      <div class="row"> 
          @php $search = Request::get('search'); @endphp
          @if(isset($search))
          <div class="cleardiv"> 
          <a href="/admin/vendors" class="btn btn-primary float-right">Clear Search</a>
          </div>
          @endif
          <div class="col-md-12">
            <form  method="get">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" value="{{ Request::get('search') }}">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                  </div>
              </form>

              <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                  <th class="th-sm">Name</th>
                  <th class="th-sm">Email</th>
                  <th class="th-sm">Collection Id</th>
                  <th class="th-sm">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @if($users->count() > 0)
                      @foreach($users as $user)
                      <tr>
                      <td>{{$user->name}}</td>
                      <td>{{$user->email}}</td>
                      <td>{{$user->type}}</td>  
                      <td>
                           <a href="vendors/{{$user->id}}"><i class="fas fa-edit"></i> Edit</a>
                           <form method="POST" action="/admin/vendor/delete/{{$user->id}}" style=" display: inline-block; ">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            {{-- <div class="form-group"> --}}
                                <button type="submit" class="btn btn-primary"><i class="fas fa-trash-alt"></i>Delete</button> 
                            {{-- </div> --}}
                           </form>

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
@endsection