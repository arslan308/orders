@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Profit</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/home">Home</a></li>
            <li class="breadcrumb-item active">Profit</li>
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
            $yms[] = "<option>All</option>";
            for($x = 12; $x >= 0; $x--) {
                $ym = date('Y-m', strtotime($now . " -$x month"));
                $yms[$ym] = '<option>'.$ym.'</option>';
            }
            $yms = array_reverse($yms);
            echo "<select class='form-control monthprofit'>";
            print_r($yms);
            echo "</select>";
        @endphp
    </div>
      </div>
    </div>
      <div class="row">
          <div class="col-md-12">
              <table class="table table-bordered" id="profittable" cellspacing="0" width="100%">
                  <thead>
                  <th class="th-sm">Client</th>
                  <th class="th-sm">Month</th>
                  <th class="th-sm">Profit</th>
                  </thead>
              </table>
          </div>
      </div>
      </div>
@endsection