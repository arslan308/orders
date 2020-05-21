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
    </div>
  </div>

  <div class="container-fluid">
<section>
@if($items->count() >0) 
  <div class="row">

    <!--Grid column-->
    <div class="col-lg-8">

      <!-- Card -->
      <div class="mb-3">
        <div class="pt-4 wish-list">

          <h5 class="mb-4">Cart items</h5>
          @php $check =0; @endphp
          @foreach($items as $item)
          @php $check++; @endphp
          <div class="row mb-4">
            <div class="col-md-5 col-lg-3 col-xl-3">
              <div class="view zoom overlay z-depth-1 rounded mb-3 mb-md-0">
                <img class="img-fluid w-100"
                  src="{{$item->products->image}}" alt="Sample">
              </div>
            </div>
            <div class="col-md-7 col-lg-9 col-xl-9">
              <div>
                <div class="d-flex justify-content-between">
                  <div>
                    <h5>{{$item->products->title}}</h5>
                  </div>
                  <div>
                    <a data-val="{{$item->product_id}}" href="javascript:;" type="button" class="delete-item card-link-secondary small text-uppercase mr-3"><i
                        class="fas fa-trash-alt mr-1"></i> Remove item </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr class="mb-4">
          @endforeach
          <p class="text-primary mb-0"><i class="fas fa-info-circle mr-1"></i> Do not delay in writing, adding
            items to your cart does not mean booking them.</p>

        </div>
      </div>
      <!-- Card -->

    </div>
    <!--Grid column-->

    <!--Grid column-->
    <div class="col-lg-4">

      <!-- Card -->
      <div class="mb-3">
        <div class="pt-4">

          <h5 class="mb-3">Cart summary</h5>

          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
              What to write
              <span>
                 <select class="form-control" name="type">
                <option value="title">Title</option>
                <option selected value="description">Description</option>
              </select>
            </span>
            </li>
            <li class="description-package list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
              Type
              <span>
                <select class="form-control" name="destype">
                  <option value="basic" dataval="3" selected>Basic 30-40 words ($3)</option>
                  <option value="advance" dataval="6">Advance 60-80 words ($6)</option>
                  <option value="premium" dataval="14">Premium 150-160 words ($14)</option>
                </select>
            </span>
            </li>

            <li class="product-title customhidden list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
              <span><span class="itemcount2">{{$check}}</span> products titles</span>
              <span class="titletotal">${{ number_format($check) * 2 }}.00</span>
            </li>
            <li class="product-description list-group-item d-flex justify-content-between align-items-center px-0">
              <span class="descount" style="display: none;">{{$check}}</span>
              <span><span class="itemcount">{{$check}}</span> products description</span>
              <span class="description-amount">${{ number_format($check) * 3 }}.00</span>
            </li>
            @if( number_format($check) * 3  <= 15)
            <li class="service-fee list-group-item d-flex justify-content-between align-items-center px-0">
              Service fee under $15 order
              <span>$1</span>
            </li>
            @endif
            <li class="description-total list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
              <div>
                <strong>Total amount</strong>
              </div>
              @if( number_format($check) * 3  <= 15)
              <span><strong class="total-amount">${{ number_format($check) * 3 + 1 }}.00</strong></span>
              @else
              <span><strong class="total-amount">${{ number_format($check) * 3  }}.00</strong></span>

              @endif
            </li>
            <li class="title-total customhidden list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
              <div>
                <strong>Total amount</strong>
              </div>
              @if( number_format($check) * 3  <= 15)
              <span><strong class="total-amount">${{ number_format($check) * 2 + 1 }}.00</strong></span>
              @else
              <span><strong class="total-amount">${{ number_format($check) * 2  }}.00</strong></span>

              @endif
            </li>
          </ul>
          <div class="form-group">
            <label>Task Title</label>
            <input type="text" class="form-control" name="title">
          </div>
          <div class="form-group">
            <label>Task Instructions (optional)</label>
            <textarea type="text" class="form-control" name="instructions"></textarea>
          </div>

          <button type="button" class="btn btn-primary btn-block">go to checkout</button>

        </div>
      </div>
      <!-- Card -->

    </div>
    <!--Grid column-->

  </div>
  @else
  Cart Is Empty
@endif
</section>
      </div>
@endsection