<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZfrShopify\ShopifyClient;
use GuzzleHttp\Client;
use Auth;
use App\User;
use App\Shop;
use App\Order;
use App\Profit;
use PDF;
use Mail;

class EmailController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index(){
        return view('email.index'); 
    }
    public function getdata(Request $request){ 
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $monthyear = $request->month;
        $monthyear = explode('-', $monthyear);
        $year = $monthyear[0];
        $month = $monthyear[1];

        $allusers =  user::where('is_admin','=','0')->get();
        $shop = Shop::all();
        $api_key = "2cedddf03d8c528fb2aab37fdf9f069e";
        $shared_secret = $shop[0]->access_token;
    
        $shopifyClient = new ShopifyClient([
            'private_app'   => true, 
            'api_key'       => $api_key,
            'password'    => $shared_secret,
            'shop'          => 'athletes-direct.myshopify.com',
            'version'       => '2020-04'
        ]);
        $countmail = 0;
        $countmailover = 0;
        $countmailunder = 0;
        foreach($allusers as $key => $user){

            $totalwin = 0;
            $today  = date('Y-m-d');
            $month_num = date('m');
            $month_name = date("F", mktime(0, 0, 0, $month_num, 10)); 
  
            $key = $key+1;
            $records = '<style>table{width:100%;border-collapse: collapse;}td { border: 1px solid; }.invoice-title h2, .invoice-title h3 { display: inline-block; } table, table td { border: solid black; } table { border-width: 1px 1px 0 0; } table td { border-width: 0 0 1px 1px; }</style><div class="logo" style="margin:20px 0px;text-align:center;"><img style="width:100px;height:100px;" src="https://cdn.shopify.com/s/files/1/0150/0758/0260/files/71588911_378170299725297_3203833293992624128_n.png?v=1598812258"></div> <div class="container"> <div class="row"> <div class="col-xs-12"> <div class="invoice-title"> <h2>Invoice</h2><h3 class="pull-right" style="float:right;"># '.$key.'</h3> </div> <hr> <div class="row"> <div class="col-xs-6" style="float:right;"> <address> <strong>Invoice To:</strong><br> '. $user->name.'<br> '.$today.' </address> </div> <div class="col-xs-6 text-right"> <address> <strong>Company Info:</strong><br> 561-212-2474<br> Jason@fanarch.com </address> </div> </div> <div class="row"> <div class="col-xs-6"> <address> </div> </div> </div> </div> <div class="row"> <div class="col-md-12"> <div class="panel panel-default"> <div class="panel-heading"> <h3 class="panel-title"><strong>Invoice summary</strong></h3> </div> <div class="panel-body"> <div class="table-responsive"> <table class="table table-condensed"> <thead> <tr> <td><strong>Date</strong></td> <td class="text-center"><strong>Order #</strong></td> <td class="text-center"><strong>Items</strong></td> <td class="text-right"><strong>Quantity</strong></td> <td class="text-right"><strong>Winnings</strong></td> </tr> </thead> <tbody>';
          
            $products = Order::orderBy('id')->whereYear('odate', '=', $year)->whereMonth('odate', '=', $month)->get()->toArray();   


        $num = $user->type;
        $int = (int)$num;
        $collproducts = $shopifyClient->getCollection(['id' => $int]);
        if(!$collproducts['title']){
            continue;
        }
        if(count($products) > 0){ 
        foreach($products as $key2 => $product){
            if($product['quantity'] == "0"){
                continue;
            }

            $costdiffper =  round($product['peritemretail'], 2);
            $costdiffper = $costdiffper / $product['quantity'];  
            $products[$key2]['costdiffper'] = $costdiffper;   
    
            $gain =  round($product['peritemcost'], 2);
            $gain = $gain / $product['quantity'];  
            $products[$key2]['difperitem'] =$gain;   

          $output = "0";
          $cost = "0";
            $products[$key2]['profitper'] = $user->profit;    //// current user profit percentage
            $check = 0;
            $allitems = '';
            foreach($product['items'] as $key => $item){
                $match = explode(' "',$product['items'][$key]['name']); 
                $match = explode(' “',$match[0]);   
                if(strtolower($match[0]) == strtolower($collproducts['title']) || strpos(strtolower($collproducts['title']), strtolower($match[0])) !== FALSE){    

                // if(strpos(strtolower($product['items'][$key]['name']), strtolower($collproducts['title'])) !== FALSE){  
                    $allitems .= $product['items'][$key]['name'];
                    $check++; 

                    $output =  $output + $product['items'][$key]['price']*$product['items'][$key]['quantity']+ $products[$key2]['difperitem'] * $product['items'][$key]['quantity'] ;
                    $cost =  $cost + $product['items'][$key]['retail_price'] * $product['items'][$key]['quantity'] +  $products[$key2]['costdiffper'] * $product['items'][$key]['quantity'];
                
                    }
                    else{ 
                         unset($products[$key2]['items'][$key]); 
                    }
                }
                    if($check == 0){
                        unset($products[$key2]);  
                        continue;                    
                    }
                    else{
                        $products[$key2]['items'] = array_values($products[$key2]['items']);
                    }
                    $output = round($output, 2);   //// getting cost of all items
                    $poutput = $cost - $output;    /// jason farmoula
                    if(!is_null($products[$key2]['profitper'])){
                    $poutput = $poutput*$products[$key2]['profitper'];   
                    $itemprofit  =  '$'.round($poutput, 2);  
                    $totalwin  = $totalwin+round($poutput, 2);
                  }
                  else{
                    $itemprofit =  "Profit Not Set";
                  }
          $records .= "<tr><td>".$product['odate']."</td><td>".$product['order_id']."</td><td>".$allitems."</td><td>".$check."</td><td>".$itemprofit."</td></tr>";

        }
        $records .= "</tbody></table></div></div></div></div></div></div>";
        $records .= "<div style='margin-top:40px;font-wight:400;display:block;width:100%;'><div class='cstmtxt' style='float:left;'>Total January Winnings</div><div style='float:right;'>$".$totalwin."</div></div>";
        if($user->carried > 0){
          $totalwin2 =  $user->carried;  
          $totalwin3 =  $totalwin2 +  $totalwin ;   
          $records .= "<br><div class='row' style='margin-top:40px;font-wight:400;'><div class='cstmtxt2 col-xs-6' style='float:left;'>Last Month Winnings</div><div class='col-xs-6' style='float:right;'>$".$totalwin2."</div></div>";
          $records .= "<br><div class='row' style='margin-top:40px;font-wight:400;'><div class='cstmtxt3 col-xs-6' style='float:left;'>Total Winnings</div><div class='col-xs-6' style='float:right;'>$".$totalwin3."</div></div>";
          
        }
  
    }
    if($totalwin > 0){  

        $countmail++;
        $pdf = PDF::loadHTML($records);
        $path = public_path().'/emailpdf/'.$user->type.$today.'.pdf';

        $pdf->save(public_path().'/emailpdf/'.$user->type.$today.'.pdf');
        $pdf->download('test.pdf'); 
        if($request->auto == 1){  
        $data["email"]= $request->testemail; 
        }else{ 
        $data["email"]= $user->email;       
       } 
        $data["client_name"]= $user->name; 
        $data["subject"] = 'Invoice';
        $utype = $user->type;  
        $txtmsg =  $request->message;  
        $txtmsg2 =  $request->message2;   
        $finalamount = $totalwin+$user->carried;
        if($finalamount <= 20 ){ 
            $countmailunder++;
            Mail::send('email.email2', array('msg' => $txtmsg2), function ($message)use($data) {
                $message->to($data["email"], $data["client_name"])
                ->subject($data["subject"]); 
            }); 
            if($request->auto == 0){   
             $allusers =  user::where('id','=',$user->id)->update(['carried' => $finalamount]);
            }
        }
        else{   
            $countmailover++;
            Mail::send('email.email', array('msg' => $txtmsg), function ($message)use($data,$utype,$today) {
                $message->to($data["email"], $data["client_name"])
                ->subject($data["subject"])
                ->attach(public_path('emailpdf/'.$utype.$today.'.pdf'), [ 
                    'as' => 'invoice.pdf',
                    'mime' => 'application/pdf',
               ]);
            });
            if($request->auto == 0){  
              $allusers =  user::where('id','=',$user->id)->update(['carried' => '0']); 
            }  
        }
    }  
    // return '1';
    // exit;    
    }
    return $countmailunder.'-'.$countmailover; 
}
}