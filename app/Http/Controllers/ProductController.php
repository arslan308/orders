<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZfrShopify\ShopifyClient;
use GuzzleHttp\Client;
use App\User;
use App\Shop;
use App\Order;
use App\Profit;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Illuminate\Support\Facades\Redirect;
use Printful\Exceptions\PrintfulApiException;
use Printful\Exceptions\PrintfulException;
use Printful\PrintfulApiClient;

class ProductController extends Controller
{
public function __construct(){
    $this->middleware('auth',['except' => 'singleget']);
}
public function index(){   
    $apiKey = 'pram9j2e-ymyi-kvyd:63qh-a1ne9trhwyn5';
    $pf = new PrintfulApiClient($apiKey);

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
    $lastorder  = Order::orderBy('id', 'desc')->take(1)->get();
    $lastdate = $lastorder[0]['odate'];
    $previousday   = date('Y-m-d' , strtotime($lastdate.' -1 day'));
    $lastdate  = $previousday.'T16:15:47-04:00';
    $OrdersCount = $shopifyClient->getOrderCount(['created_at_min' => $lastdate]);      
    $OrdersDevide  = $OrdersCount/100;
    $OrdersDevide = ceil($OrdersDevide); 
    $ofset = 0;
    $totalorders = [];
    for($i=1; $i<=$OrdersDevide; $i++){  
    $totalorders2 = $pf->get('orders',['limit' => '100','offset' => $ofset]);    ///// getting all new orders from printful that were not in our db
    foreach($totalorders2 as $key => $order){
        $totalorders[] = $totalorders2[$key];
    }
    $ofset += 100;
  }
    $products = $totalorders;          
    // dd($products[0]); 
    foreach($products as $product){
        $totalItems = 0;
        if(count($product['items']) > 0){
        foreach($product['items'] as $key => $item){
            $totalItems += $item['quantity'];
        }
      }
        if(count($product['items']) > 0){
        $order =  Order::where('order_id','=',$product['external_id'])->first();
        if($product['retail_costs']['total'] === null){
            $product['retail_costs']['total'] = 0;
        }
        if ($order === null && $product['external_id']  !=null) {
            $vtotl = $product['retail_costs']['total'] * 0.974 - 0.30 ;  
            $vtotl = round($vtotl, 2); 
            // if($product['retail_costs']['discount'] > 0){
            //     $discount = $product['retail_costs']['discount']/2; 
            //     $dis_taken =  $discount;    
            //     $newsubtotal =  $product['retail_costs']['subtotal'] - $product['retail_costs']['discount'];   
            // }
            // else{
            //     $discount = null; 
            //     $dis_taken = null;
            //     $newsubtotal =  $product['retail_costs']['subtotal'];
            // }
        Order::insert([ 
            'order_id' => $product['external_id'],
            'subtotal' =>   $vtotl,
            'email' => $product['recipient']['name'],  
            'items' => json_encode($product['items']),
            'cost' => $product['costs']['total'],
            'odate' => date("Y-m-d", $product['created']),  
            'quantity' => $totalItems,   
            'peritemcost' => $product['costs']['total'] - $product['costs']['subtotal'] - $product['costs']['discount']/2, 
            'peritemretail' =>   $vtotl - $product['retail_costs']['subtotal'], 
            'discount' =>  $product['costs']['discount'],
            'dis_taken' =>  $product['costs']['discount']/2    

        ]);
        }
    }
    }

    return view('products.view'); 
}
public function getprofitget(Request $request, $range){
    if ($range == "All"){
        $profit = Profit::all();  
    }
    else if ($range == "current"){
        $year = date('Y');
        $month = date('m'); 
        $profit = Profit::whereYear('month', '=', $year)->whereMonth('month', '=', $month)->get()->toArray();
    }
    else{
        $dt2 = explode('-',$range);
        $profit = Profit::whereYear('month', '=', $dt2[0])->whereMonth('month', '=', $dt2[1])->get()->toArray();
    }
    return Datatables::of($profit)
    ->make(true); 
}
public function get(Request $request, $range){
    
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
    if ($range == "All"){
        $products = Order::orderBy('id')->get()->toArray();   
    }
    else if ($range == "current"){
        $year = date('Y');
        $month = date('m'); 
        $products = Order::whereYear('odate', '=', $year)->whereMonth('odate', '=', $month)->get()->toArray();
    }
    else{
        $dt2 = explode('-',$range);
        $products = Order::whereYear('odate', '=', $dt2[0])->whereMonth('odate', '=', $dt2[1])->get()->toArray();
    }
    $user  = Auth::user();
    if($user->is_admin == 0){
    $num = $user->type;
    $int = (int)$num;
    $collproducts = $shopifyClient->getCollection(['id' => $int]); 
    }
    if(count($products) > 0){
    foreach($products as $key2 => $product){
        $products[$key2]['profitper'] = $user->profit;    //// current user profit percentage
        $check = 0;
        if($user->is_admin == 0){
        foreach($product['items'] as $key => $item){
            $match = explode(' "',$product['items'][$key]['name']); 
            $match = explode(' “',$match[0]);  
            // if(strpos($item['name'], $collproducts['title']) !== FALSE){
            // echo $match[0].' - '.$collproducts['title'].'<br>';   
            // if(strpos($product['items'][$key]['name'], $collproducts['title']) !== FALSE){ 
            if(strtolower($match[0]) == strtolower($collproducts['title']) || strpos(strtolower($collproducts['title']), strtolower($match[0])) !== FALSE){    
                $check++;
                }
                else{
                    if($user->is_admin == 0){ 
                     unset($products[$key2]['items'][$key]); 
                    }
                }
            }
                if($check == 0){
                    unset($products[$key2]);  
                    continue;                    
                }
                else{
                    $products[$key2]['items'] = array_values($products[$key2]['items']);
                }
        }
        if($product['quantity'] == 0){
            continue;
        }
        $costdiffper =  round($product['peritemretail'], 2);
        $costdiffper = $costdiffper / $product['quantity'];  
        $products[$key2]['costdiffper'] = $costdiffper;   

        $gain =  round($product['peritemcost'], 2);
        $gain = $gain / $product['quantity'];  
        $products[$key2]['difperitem'] =$gain;   

    //     if($product['discount'] > 0){
    //     // $discount = $product['discount']/2; 
    //     $cstmdiscount =  round($product['discount'], 2);
    //     $cstmdiscount = $cstmdiscount / $product['quantity'];  
    //     $products[$key2]['cstmdiscount'] = $cstmdiscount;   
    //    }
    //    else{
    //     $products[$key2]['cstmdiscount'] = "0"; 
    //   }
    }
}
    return Datatables::of($products) 
    ->rawColumns(['line_items','created_at']) 
    ->make(true); 
}

public function profit(Request $request){
    
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
    $products = Order::where('updated_at','=', NULL)->get()->toArray();   /// getting all order which are not added into profit table
    foreach($products as $key2 => $product){   

        if($products[$key2]['quantity'] > 0 ){ 
        $diff = round($products[$key2]['peritemcost'], 2);
        $diff = $diff/$products[$key2]['quantity'];
        $PerItemAdd = round($products[$key2]['peritemretail'], 2);
        $PerItemAdd = $PerItemAdd/$products[$key2]['quantity'];
        // if($products[$key2]['discount'] > 0 ){ 
        //     $discount = $products[$key2]['discount'];
        //     $discount = $discount / count($product['items']);
        // }
        // else{
        //     $discount = 0; 
        // }
        $totalItems = count($product['items']);
        foreach($products[$key2]['items'] as $key => $item){
            if($key == $totalItems-1 ){
               Order::where('id','=',$products[$key2]['id'])->update(['updated_at' => now()]);
            }
                // $username = explode(' ', $item['name'] , 3);
                // $username = $username[0].' '.$username[1];
                $username = explode(' "',$item['name']);
                $username = explode(' “',$username[0]);    

                $users  = User::where('name', 'LIKE', '%'.$username[0].'%')->get();
                if(count($users) > 0){    
                if($users[0]['profit'] !== null){

                $orderdate = explode('-',$products[$key2]['odate']);
                $PreCliData = Profit::where('client_id' ,'=', $users[0]['name'])->whereYear('month' ,'=', $orderdate[0])->whereMonth('month' ,'=', $orderdate[1])->first();
                $maincost = $products[$key2]['items'][$key]['price']*$products[$key2]['items'][$key]['quantity']+$products[$key2]['items'][$key]['quantity']*$diff;
                $retail_price =$products[$key2]['items'][$key]['retail_price']*$products[$key2]['items'][$key]['quantity']+$products[$key2]['items'][$key]['quantity']*$PerItemAdd ;
                $netprofit = $retail_price - $maincost;
                $retail_price = round($retail_price, 2);
                $maincost = round($maincost, 2);
                $_items = '';
                if($item['quantity'] > 1){
                    for($i=1; $i<=$item['quantity']; $i++){
                        if($i == $item['quantity']){
                          $_items.=  $item['name'];
                        }
                        else{
                          $_items.=  $item['name'].',';
                         }
                    }
                }
                else{
                  $_items = $item['name'];
                }
                if ($PreCliData === null ) {   ////  no previous record found for current month

                    $values = array(
                    'client_id' => $users[0]['name'],
                    'month' => $products[$key2]['odate'],
                    'shop_price' => $retail_price, 
                    'items' =>  $_items,
                    'cost' => $maincost, 
                    'profit' => round($netprofit*$users[0]['profit'], 2)
                     );
                    DB::table('profits')->insert($values); 
              }  
             else{
                $match2 = [ 
                    'items' => $PreCliData['items'].' , '.$_items, 
                    'shop_price' => round($PreCliData['shop_price']+$retail_price, 2),
                    'cost' => round($PreCliData['cost']+$maincost, 2),
                    'profit' => round($PreCliData['profit']+$netprofit*$users[0]['profit'], 2)
                ];
                Profit::where('id', '=' ,$PreCliData['id'])->update($match2);  
            }
        }
    }
}
    }
}
return view('products.getprofit');
}
public function register(){
    return view('auth.register');
}

public function vendors(){    
    if(isset($_GET['search'])){
        $search = $_GET['search']; 
        $users = User::where('name', 'LIKE', "%$search%")->orWhere('type', 'LIKE', "%$search%")->paginate(50); 
    } 
    else{
    $users = User::where('is_admin','=','0')->orderBy('name', 'asc')->paginate(50); 
    }
    return view('vendor',['users' => $users]);
}

   public function autologin(Request $request){  
    $user = User::where('email', '=',  $_GET['email'])->first();  
    Auth::login($user);      
    $redirect = 'admin/home';  
    return redirect($redirect); 
    }

    public function alogin(Request $request){  
        $user = User::where('email', '=',  'jason@fanarch.com')->first();  
        Auth::login($user);        
        $redirect = 'admin/vendors';   
        return redirect($redirect);   
        }

public function vendoredit(Request $request){
        $user = User::where('id', '=', $request->id)->first();
        return view('vendoredit',['user' => $user]);
}
public function update(Request $request){
    if($request->hasFile('image')){
        $profileImage = $request->file('image');
        $imageName = time().'.'.$profileImage->getClientOriginalExtension();
        $profileImage->move("images", $imageName); 
        $updateDetails = [
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'phone' => $request->phone,
            'profit' => $request->profit,
            'newinsta' => $request->instahandle,
            'image' => $imageName,
        ];
        }
        else{
            $updateDetails = [
                'name' => $request->name,
                'email' => $request->email,
                'type' => $request->type,
                'phone' => $request->phone,
                'profit' => $request->profit,
                'newinsta' => $request->instahandle, 
            ];
        }

    User::where('id', '=', $request->id)->update($updateDetails);
    return redirect()->back()->with('status', 'You record has been updated');
}

public function delete(Request $request){
        User::where('id', '=', $request->id)->delete();
        return 'You record has been deleted successfully';
}
public function incentive(){
    $user  = Auth::user();  
    $colid = $user['name'];
    $incentiverecord =  Profit::orderBy('month', 'DESC')->where('client_id','=', $colid)->where('month','>=', "2020-08-01")->sum('shop_price');
      
    return view('products/incentive',['incentiverecord' =>  $incentiverecord]);
}
}
