@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Products</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/home">Home</a></li>
            <li class="breadcrumb-item active">Products</li>
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
          <div class="col-md-12">
              <table class="table table-bordered" id="productstable" cellspacing="0" width="100%">
                  <thead>
                  {{-- <th><input type="checkbox" name="parent"></th> --}}
                  <th class="th-sm">Customer Email</th>
                  <th class="th-sm">Total</th>
                  <th class="th-sm">Date</th>
                  <th class="th-sm">Items</th>
                  <th class="th-sm">Quantity</th>
                  </thead>
              </table>
          </div>
      </div>
      </div>
@endsection