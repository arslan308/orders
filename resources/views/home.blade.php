@extends('layouts.admin')

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
    <p class="handle" style="display: none;">{{ Auth::user()->newinsta }}</p> 
      @php $check1 =0; @endphp
      @foreach ($records2 as $record)
          @php 
          $check1 += $record['profit']; 
          @endphp
      @endforeach
      @php $check =0; @endphp
      @foreach ($records as $record)
          @php 
          $check += $record['profit'];
          @endphp
      @endforeach
      @php $tchk =0; @endphp
     
          <div class="row"> 
            <div class="col-md-12">
              <div class="fb-profile"> 
                <img style=" width: 100%;background: brown;" align="left" class="fb-image-lg" src="{{ asset('/public/images/107891952_208934780402134_14505967031690820_n.jpg') }}" alt="Profile image example"/>
              <div class="mainrel">
                <div class="circular--landscape">
                <img src="{{ asset('/public/images/'.Auth::user()->image) }}" alt="Profile image example"/> 
               </div>
              </div>
              </div> 
            </div>
          </div>
          <div class="row" style="margin-top:20px;">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>$@php echo $check1 @endphp</h3>
  
                  <p>Total Winnings</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer"><i class="fas fa-arrow-circle-up"></i></a> 
              </div>
            </div> 
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>$@php echo $check @endphp</h3>
  
                  <p> Monthly Profit</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer"><i class="fas fa-arrow-circle-up"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">

                <h3>{{$totalitems}}</h3>
  
                  <p title="This is total products sold current month">Total Products Sold</p> 
                </div>
                <div class="icon">
                  <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </div>
                <a href="#" class="small-box-footer"><i class="fas fa-arrow-circle-up"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                <h3>{{$growth}}</h3>  
  
                  <p>Growth</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
          </div>
           @if(Auth::user()->is_admin == 0) 
           <h2>Incentive Bar</h2> 

           <div class="row"> 
            <div class="col-sm-4">  
              <div class="card">
                <div class="card-header">Current Incentive</div>
                <div class="card-body">
                  <?php  $target = 0 ;?> 
                  @if($incentiverecord >= 0 && $incentiverecord < 750)
                  N/A
                  <?php  $target = "Bronze - $750" ?>
                @elseif($incentiverecord > 750 && $incentiverecord < 2000)
                <?php  $target = "Silver - $2000" ?>
                Bronze
                @elseif($incentiverecord > 2000 && $incentiverecord < 5000)
                Silver
                <?php  $target =  "Gold - $5000" ?>
                @elseif($incentiverecord > 5000 && $incentiverecord < 7500)
                Gold  
                <?php  $target = "Platinum - $7500" ?>
                @elseif($incentiverecord > 7500 && $incentiverecord < 10000)
                Platinum  
                <?php  $target = "Executive - $10000"  ?>
                @else 
                <?php  $target = "All Target Achieved" ?>
                Executive 
                @endif
                </div>
              </div>
            </div>
            <div class="col-sm-4">  
              <div class="card">
                <div class="card-header">Total Accumulated Sales</div>
                <div class="card-body">${{$incentiverecord}}</div> 
              </div>
            </div>
            <div class="col-sm-4">  
              <div class="card">
                <div class="card-header">Target Tier 
                </div>
                <div class="card-body">{{ $target}}</div>  
              </div>
            </div>
           </div>
          <div class="row"> 
            <div class="col-md-12">   
              <div class="incentive"> 
              <div class="first" title="Need total of 0 to unlock this"><div class="inner"><div class="inchild"><span class="inc">Bronze</span> <i class="fa fa-trophy" aria-hidden="true"></i> @if($incentiverecord > 750 )<i class="fa fa-check" aria-hidden="true"></i> @else <i class="fa fa-lock" aria-hidden="true"></i> @endif</div></div></div>
                <div class="second" title="Need total of 1500 to unlock this"><div class="inner"><div class="inchild"><span class="inc">Silver</span> <i class="fa fa-diamond" aria-hidden="true"></i> @if($incentiverecord > 2000)<i class="fa fa-check" aria-hidden="true"></i> @else <i class="fa fa-lock" aria-hidden="true"></i> @endif</div></div></div>
                <div class="third" title="Need total of 4000 to unlock this"><div class="inner"><div class="inchild"><span class="inc">Gold</span> <i class="fa fa-star" aria-hidden="true"></i> @if($incentiverecord > 5000)<i class="fa fa-check" aria-hidden="true"></i> @else <i class="fa fa-lock" aria-hidden="true"></i> @endif</div>  </div></div>
                <div class="fourth" title="Need total of 6000 to unlock this"><div class="inner"><div class="inchild"><span class="inc">Platinum </span><i class="fa fa-trophy" aria-hidden="true"></i>  @if($incentiverecord > 7500)<i class="fa fa-check" aria-hidden="true"></i> @else <i class="fa fa-lock" aria-hidden="true"></i> @endif</div> </div></div> 
                <div class="five" title="Need total of 1000 to unlock this"><div class="inner"><div class="inchild"><span class="inc">Executive</span><i class="fa fa-coffee" aria-hidden="true"></i> @if($incentiverecord > 10000)<i class="fa fa-check" aria-hidden="true"></i> @else <i class="fa fa-lock" aria-hidden="true"></i> @endif</div>  </div></div>

              </div> 
            </div>
          </div> 
          @endif
          @if (Auth::user()->is_admin == 0)
      <div class="row">
        <div class="col-md-6"> 

          <div class="card">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
              <h3 class="card-title">
                <i class="fas fa-chart-pie mr-1"></i> 
                Total Sales
              </h3>
            </div>
            <div class="card-body">  
              <div id="linechart" style="height: 370px; width: 100%;"></div> 
            </div>
          </div>

        </div>

        <div class="col-md-6">
          <div class="card" style="width: 100%;">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
              <h3 class="card-title">
                <i class="fas fa-chart-pie mr-1" aria-hidden="true"></i> 
                Sale by Product Type
              </h3>
            </div> 
            <div class="card-body">  
          <div id="piechart" style="height: 370px; width: 100%;"></div>
            </div>
          </div>
      </div>
      </div>
      @endif

    <div class="profile-user">
      <div class="row">
          <div class="col-md-4 cstmimge">
              <div class="pull-left" style="margin-right: 15px;">
                  <img style="border: 5px solid gray;" src="https://images.unsplash.com/photo-1513721032312-6a18a42c8763?w=152&h=152&fit=crop&crop=faces"
                  class="img-polaroid img-responsive img-circle" /> 
              </div>
          </div>
          <div class="col-md-8">
            <h1 class="profile-user-name" >janedoe_</h1>
            <p class="bio"></p>
              <ul class="inline user-counts-list" style=" padding-left: 0px;">
                  <li style=" padding-left: 0px;">Posts <span class="postcount"> 100</span>
                  </li>
                  <li>Followers <span class="followcount">50</span>
                  </li>
                  <li>Following <span class="chasecount">130</span>
                  </li>
              </ul>
          </div>
      </div>
  </div>
  @if (Auth::user()->is_admin == 0)
  <div class="row">
    <div class="col-md-12">
      <div class="card cstmcard"> 
        <div class="card-title">
              <i class="fas fa-chart-pie mr-1"></i> 
              Announcments
        </div> 
            <div class="card-body cstmbody">  
        @foreach($announce as $ann)
        <div class="row no-gutters cstmy"> 
          <div class="col-md-4">
            <img src="{{ asset('public/images') }}/{{  $ann->image }}"  class="card-img">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h4 class="card-title"><strong>{{ $ann->title }}</strong></h4>
            <p class="card-text" title="{{$ann->description}}">{{ str_limit($ann->description, 80) }}</p>
              <p class="card-text"><small class="text-muted">Dated: {{ $ann->created_at }}</small></p>
            </div>
          </div>
        </div>
        <hr>
        @endforeach
            </div>
      </div>
    </div>

  </div>
  @endif
    </div>
  </section>

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js"></script> 

<script>
  window.onload = function () {
  ////line chart
  var chart = new CanvasJS.Chart("linechart", {
    title: {
      text: "Total Accumulated Sales" 
    },
    axisY: {
      title: "Number of Sale"
    },
    data: [{
      type: "line",
      dataPoints: <?php echo json_encode($allsale, JSON_NUMERIC_CHECK); ?>
    }]
  });
  chart.render();
   

  ///pie chart

  var chart = new CanvasJS.Chart("piechart", {
	animationEnabled: true,
	title: {
		text: "Sale by Product type"
	},
	data: [{
		type: "pie",
		// yValueFormatString: "#,##0.00\"%\"", 
		indexLabel: "{label} ({y})",
		dataPoints: <?php echo json_encode($ptypes, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
  }
  </script>