@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
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
               
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-shopping-cart"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Total Monthly Sales</span>
                      @php $check =0; @endphp
                      @foreach ($records as $record)
                          @php 
                          $check += $record['shop_price'];
                          @endphp
                      @endforeach
                      <span class="info-box-number">@php echo $check @endphp </span> 
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
      
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
      
                <div class="col-12 col-sm-6 col-md-4">  
                  <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fa fa-plus"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Monthly Profit </span>
                      @php $check =0; @endphp
                      @foreach ($records as $record)
                          @php 
                          $check += $record['profit'];
                          @endphp
                      @endforeach
                      <span class="info-box-number">@php echo $check @endphp  </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-fire"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Most Popular Product</span>
                    <span title="{{$popular}}" class="info-box-number" style=" font-size: 14px;">{{ str_limit($popular, $limit = 30, $end = '...') }}</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
              </div>
        </div>
      </section>
      <!-- /.content -->
@endsection
