@extends('layouts.admin')

@section('content')
<section class="content">
  <div class="container-fluid"> 
<div class="row">
  <div class="col-md-12">
    <img style=" width: 100%; " src="{{ asset('/public/images/Fan-Arch-Rewards-Banner.jpg') }}">
    <img style=" width: 100%; " src="{{ asset('/public/images/Fan-Arch-Website-Graphics.png') }}">


  </div>
</div>
<div class="row" style=" padding: 12px;">
  <div class="col-md-12">
   Fan Arch rewards is our way of helping enhance your store by setting sales targets. These rewards aim to grow your brand and improve your storefront. Rewards range from exclusive products added to your store to social media giveaway campaigns as well additional logo designs and new features on your Fan Arch Storefront. As you progress through Fan Arch reward tiers, you will see your sales grow as you unlock more benefits and aim to become a member of the Fan Arch Executive Club.
</div>
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
<!-- Main content -->

     <div class="row">
<div class="col-md-12">
    
<div class="wrapper center-block">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
      <div class="panel-heading active" role="tab" id="headingOne">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            Tier 1 BRONZE $750
          </a>
        </h4>
      </div>
      <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
          <ul>
          <li> (1) Free Logo Design</li>
            <li> Social Media Giveaway Campaign </li>    
          </ul>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingTwo">
        <h4 class="panel-title">
          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Tier 2 SILVER $2000
          </a>
        </h4>
      </div>
      <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
        <div class="panel-body">
          <ul>
          <li>(1) Free T-Shirt
            <li> Additional Product added to store</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingThree">
        <h4 class="panel-title">
          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Tier 3 GOLD $5000
          </a>
        </h4>
      </div>
      <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
        <div class="panel-body">
          <ul>
            <li> Free Logo Design</li>
              <li> Social Media Giveaway Campaign</li>
                <li> Exclusive Products Unlocked for store</li>
                  <li>Joint Charity Campaign</li>
                    <li> (1) Free Hoodie</li>
                      <li>(1) Graphic Design</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingThree">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsefour" aria-expanded="false" aria-controls="collapseThree">
               Tier 4 PLATINUM $7500

            </a>
          </h4>
        </div>
        <div id="collapsefour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
          <div class="panel-body">
            <ul>
            <li> (1) Free Logo Design</li>
              <li> Social Media Giveaway Campaign</li>
                <li>Front Page Cycle</li>
              </ul>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingThree">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsefive" aria-expanded="false" aria-controls="collapseThree">
              Tier 5 EXECUTIVE $10000

            </a>
          </h4>
        </div>
        <div id="collapsefive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
          <div class="panel-body">
            <ul>
            <li> Fan Arch Executive Club Membership</li>
              <li> (1) Free Logo Design with Ownership</li>
                <li> Huge Social Media Giveaway</li>
                  <li> Giveback Ownership % on Store</li>
                    <li> Front Page Cycle</li>
                      <li> Exclusive Executive Products Added to Store</li>
                        <li> Graphic Design</li>
                          <li> Additional features on Fan Store</li>
            </ul>
          </div>  
        </div>
      </div>
  </div>
  </div>
</div>

     </div>
    </div>
  </section>
  <style>
.panel-body {
    padding: 20px 0px;
}
@media(max-width:992px){
 .wrapper{
  width:100%;
} 
}
.panel-heading {
  padding: 0;
	border:0;
}
.panel-title>a, .panel-title>a:active{
    display:block;
    border: 1px solid #e2e2e2;
	padding:15px;
  color:#555;
  font-size:16px;
  font-weight:bold;
	text-transform:uppercase;
	letter-spacing:1px;
  word-spacing:3px;
	text-decoration:none;
}
.panel-heading  a:before {
   font-family: 'Glyphicons Halflings';
   content: "\e114";
   float: right;
   transition: all 0.5s;
}
.panel-heading.active a:before {
	-webkit-transform: rotate(180deg);
	-moz-transform: rotate(180deg);
	transform: rotate(180deg);
} 
  </style>
@endsection
