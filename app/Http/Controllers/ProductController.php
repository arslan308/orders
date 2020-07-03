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
    // dd($OrdersCount);
    //  $shopifyClient->getOrderCount()
    $OrdersDevide  = $OrdersCount/100;
    $OrdersDevide = ceil($OrdersDevide); 
    // dd($OrdersDevide);
    $ofset = 0;
    $totalorders = [];
    for($i=1; $i<=$OrdersDevide; $i++){
    $totalorders2 = $pf->get('orders',['limit' => '100','offset' => $ofset]);
    foreach($totalorders2 as $key => $order){
        $totalorders[] = $totalorders2[$key];
    }
    $ofset += 100;
  }
//   dd($totalorders[53]); 
    $products = $totalorders;
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
            if($product['retail_costs']['discount'] > 0){
                $discount = $product['retail_costs']['discount'];
                $newsubtotal =  $product['retail_costs']['subtotal'] - $product['retail_costs']['discount'];
            }
            else{
                $discount = null;
                $newsubtotal =  $product['retail_costs']['subtotal'];
            }
        Order::insert([ 
            'order_id' => $product['external_id'],
            'subtotal' =>   $vtotl,
            'email' => $product['recipient']['name'],  
            'items' => json_encode($product['items']),
            'cost' => $product['costs']['total'],
            'odate' => date("Y-m-d", $product['created']),  
            'quantity' => $totalItems, 
            'peritemcost' => $product['costs']['total'] - $product['costs']['subtotal'], 
            'peritemretail' =>   $vtotl - $newsubtotal,
            'discount' =>  $discount

        ]);
        }
    }
    }

    return view('products.view');
}
public function getprofitget(Request $request, $range){
    if ($range != "current"){
        $dt2 = explode('-',$range);
        $profit = Profit::whereYear('month', '=', $dt2[0])->whereMonth('month', '=', $dt2[1])->get()->toArray();
    }
    else{
        $year = date('Y');
        $month = date('m'); 
        $profit = Profit::whereYear('month', '=', $year)->whereMonth('month', '=', $month)->get()->toArray();
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
    if ($range != "current"){
        $dt2 = explode('-',$range);
        $products = Order::whereYear('odate', '=', $dt2[0])->whereMonth('odate', '=', $dt2[1])->get()->toArray();
    }
    else{
        $year = date('Y');
        $month = date('m'); 
        $products = Order::whereYear('odate', '=', $year)->whereMonth('odate', '=', $month)->get()->toArray();
    }
    $user  = Auth::user();
    if($user->is_admin == 0){
    $num = $user->type;
    $int = (int)$num;
    $collproducts = $shopifyClient->getCollection(['id' => $int]);
    }
    if(count($products) > 0){
        // dd($products);
    foreach($products as $key2 => $product){
        $products[$key2]['profitper'] = $user->profit;    //// current user profit percentage
        $check = 0;
        if($user->is_admin == 0){

        foreach($product['items'] as $key => $item){
            if(strpos($item['name'], $collproducts['title']) !== FALSE){
                $check++;
                }
                else{
                    if($user->is_admin == 0){
                     unset($product['items'][$key]); 
                    }
                }
            }
                if($check == 0){
                    unset($products[$key2]);  
                }
        }
        if($product['quantity'] == "0"){
            continue;
        }
        $costdiffper =  round($product['peritemretail'], 2);
        $costdiffper = $costdiffper / $product['quantity'];  
        $product['costdiffper'] = $costdiffper;   

        $gain =  round($product['peritemcost'], 2);
        $gain = $gain / $product['quantity'];  
        $product['difperitem'] =$gain;   

        if($product['discount'] > 0){
        $cstmdiscount =  round($product['discount'], 2);
        $cstmdiscount = $cstmdiscount / $product['quantity'];  
        $product['cstmdiscount'] = $cstmdiscount;   
       }
       else{
        $product['cstmdiscount'] = "0"; 
      }
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
        // $diff = round($diff, 2);
        $PerItemAdd = round($products[$key2]['peritemretail'], 2);
        $PerItemAdd = $PerItemAdd/$products[$key2]['quantity'];
        // $PerItemAdd = round($PerItemAdd, 2);
        if($products[$key2]['discount'] > 0 ){ 
            $discount = $products[$key2]['discount'];
            $discount = $discount / count($product['items']);
            // $discount = round($discount, 2);
        }
        else{
            $discount = 0;
        }
        $totalItems = count($product['items']);
        foreach($products[$key2]['items'] as $key => $item){
            // dd($product['items']);
            if($key == $totalItems-1 ){
               Order::where('id','=',$products[$key2]['id'])->update(['updated_at' => now()]);
            }
                $username = explode(' ', $item['name'] , 3);
                $username = $username[0].' '.$username[1];
                $users  = User::where('name', 'LIKE', '%'.$username.'%')->get();
                if(count($users) > 0){
                if($users[0]['profit'] !== null){

                $orderdate = explode('-',$products[$key2]['odate']);
                $PreCliData = Profit::where('client_id' ,'=', $users[0]['name'])->whereYear('month' ,'=', $orderdate[0])->whereMonth('month' ,'=', $orderdate[1])->first();
                $maincost = $products[$key2]['items'][$key]['price']*$products[$key2]['items'][$key]['quantity']+$products[$key2]['items'][$key]['quantity']*$diff;
                $retail_price =$products[$key2]['items'][$key]['retail_price']*$products[$key2]['items'][$key]['quantity']+$products[$key2]['items'][$key]['quantity']*$PerItemAdd - $discount;
                $netprofit = $retail_price - $maincost;
                $retail_price = round($retail_price, 2);
                $maincost = round($maincost, 2);

                // $netprofit = round($netprofit, 2);  
                if ($PreCliData === null ) {   ////  no previous record found for current month

                    $values = array(
                    'client_id' => $users[0]['name'],
                    'month' => $products[$key2]['odate'],
                    'shop_price' => $retail_price, 
                    'items' =>  $item['name'],
                    'cost' => $maincost, 
                    'profit' => round($netprofit*$users[0]['profit'], 2)
                     );
                    DB::table('profits')->insert($values); 
              }  
             else{
                $match2 = [
                    'items' => $PreCliData['items'].' , '.$item['name'], 
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
        $users = User::where('name', 'LIKE', "%$search%")->paginate(10);
    } 
    else{
    $users = User::where('is_admin','=','0')->orderBy('name', 'asc')->paginate(50);
    }
    return view('vendor',['users' => $users]);
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
            ];
        }

    User::where('id', '=', $request->id)->update($updateDetails);
    return redirect()->back()->with('status', 'You record has been updated');
}

public function delete(Request $request){
        User::where('id', '=', $request->id)->delete();
        return 'You record has been deleted successfully';

}
}
