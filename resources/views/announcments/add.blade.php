@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Add Announcements</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Add Announcements</li>
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
         <div class="col-md-2"></div>
         <div class="col-md-8">
         <form method="post" action="{{ route ('admin.submit') }}" enctype="multipart/form-data">
          @csrf
                 <div class="form-group">
                     <label>Title</label>
                     <input class="form-control" name="title" type="text" required>
                 </div>

                 <div class="form-group">
                    <label>Description</label>
                    <input class="form-control" name="description" type="text" required>
                </div>

                <div class="form-group">
                    <label>Image</label>
                    <input class="form-control" name="image" type="file" required>
                </div>

                <div class="form-group">
                    <input class="btn btn-success" name="submit" type="submit">
                </div>
             </form>
         </div>

     </div>
    </div>
  </section>
@endsection
