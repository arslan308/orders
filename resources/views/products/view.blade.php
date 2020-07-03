@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Orders</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/home">Home</a></li>
            <li class="breadcrumb-item active">Orders</li>
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
      <div class="col-md-8">
      </div>
      <div class="col-md-4">
        <div>
          @php
            $yms = array();
            $now = date('Y-m');
            for($x = 12; $x >= 0; $x--) {
                $ym = date('Y-m', strtotime($now . " -$x month"));
                $yms[$ym] = '<option>'.$ym.'</option>';
            }
    
            echo "<select class='form-control lastmonths'>";
            print_r($yms);
            echo "</select>";
        @endphp
    </div>
      </div>
    </div>
      <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered" id="productstable" cellspacing="0" width="100%">
                  <thead>
                  <th class="th-sm">Date</th>
                  <th class="th-sm">Order #</th> 
                  <th class="th-sm">Item Name</th>
                  <th class="th-sm">Quantity</th>
                  <th class="th-sm">Customer Name</th>
                  <th class="th-sm">Winnings</th>
                  </thead>
              </table>
            </div>
          </div>
      </div>
      </div>
@endsection