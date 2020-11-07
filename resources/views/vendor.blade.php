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
           <a href="alogin" class="btn btn-primary">Auto login</a>  
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
                  <th class="th-sm">Name</th>
                  <th class="th-sm">Email</th>
                  <th class="th-sm">Collection ID</th>
                  <th class="th-sm">Profit</th>

                  <th class="th-sm">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @if($users->count() > 0)
                      @foreach($users as $user)
                      <tr>
                      <td class="name">{{$user->name}}</td>
                      <td class="email">{{$user->email}}</td>
                      <td class="type">{{$user->type}}</td>  
                      <td class="profit">{{$user->profit}}</td>   

                      <td>
                           <button class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</button> 
                           <button type="button" class="btn btn-danger delete btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
                           <button type="button" class="btn btn-danger autologin btn-sm"><i class="fas fa-eye"></i> Login</button>  

                      </td>
                      <td class="phone" style="display: none;">{{$user->phone}}</td> 
                      <td class="id" style="display: none;">{{$user->id}}</td>
                      {{-- <td class="profit" style="display: none;">{{$user->profit}}</td>      --}}
                      <td class="instahandle" style="display: none;">{{  $user->newinsta }}</td>
                      <td class="image" style="display: none;">{{ asset('public/images') }}/{{  $user->image }}</td> 

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


      @if($users->count() > 0)
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit  Info</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
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
                  <label>Instagram Handle</label>
                  <input type="text" name="instahandle" class="form-control" value="{{ $user->newinsta }}">
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
            <div class="modal-footer">
            </div>
          </div>
        </div>
      </div>
      @endif
@endsection