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
          <div class="col-md-12">
              <table class="table table-bordered" id="profittable" cellspacing="0" width="100%">
                  <thead>
                  <th class="th-sm">Client</th>
                  <th class="th-sm">Month</th>
                  {{-- <th class="th-sm">Cost</th> --}}
                  <th class="th-sm">Profit</th>
                  </thead>
              </table>
          </div>
      </div>
      </div>
@endsection