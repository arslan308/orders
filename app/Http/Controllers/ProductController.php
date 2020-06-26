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
    $OrdersCount = $shopifyClient->getOrderCount();
    $OrdersDevide  = $OrdersCount/100;
    $OrdersDevide = ceil($OrdersDevide); 
    $ofset = 0;
    $totalorders = [];
    for($i=1; $i<=$OrdersDevide; $i++){
    $totalorders2 = $pf->get('orders',['limit' => '100','offset' => $ofset]);
    // dd($totalorders2);
    foreach($totalorders2 as $key => $order){
        $totalorders[] = $totalorders2[$key];
    }
    $ofset += 100;
  }
    $products = $totalorders;
    foreach($products as $product){
        $order =  Order::where('order_id','=',$product['external_id'])->first();
        if($product['retail_costs']['total'] === null){
            $product['retail_costs']['total'] = 0;
        }
        if ($order === null && $product['external_id']  !=null) {
        Order::insert([
            'order_id' => $product['external_id'],
            'subtotal' =>  $product['retail_costs']['total'],
            'email' => $product['recipient']['name'],
            'items' => json_encode($product['items']),
            'cost' => $product['costs']['total'],
            'odate' => date("Y-m-d", $product['created']),
            'quantity' => count($product['items'])
        ]);
        }
    }

    return view('products.view');
}
public function getprofitget(){
    $profit = Profit::all();
    return Datatables::of($profit)
    ->make(true); 
}
public function get(Request $request){
    
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
    if ($request->month){
        $dt2 = explode('-',$request->month);
        $products = Order::whereYear('odate', '=', $dt2[0])->whereMonth('odate', '=', $dt2[1])->get()->toArray();
    }
    else{
    $products = Order::all()->toArray();
    }
    $user  = Auth::user();
    if($user->is_admin == 0){
    $num = $user->type;
    $int = (int)$num;
    $collproducts = $shopifyClient->getCollection(['id' => $int]);
    }
    foreach($products as $key2 => $product){
        $products[$key2]['profitper'] = $user->profit;    //// current user profit percentage
        $check = 0;
        $checktest="0";
        $cost ="0";
        foreach($product['items'] as $key => $item){
            $checktest += $product['items'][$key]['price']*$product['items'][$key]['quantity'];  //// getting total price without tax and other things
            $cost += $product['items'][$key]['retail_price']*$product['items'][$key]['quantity'];  //// getting total price without tax and other things
           
            if($user->is_admin == 0){
            if(strpos($item['name'], $collproducts['title']) !== FALSE){
                $check++;
                }
                else{
                    if($user->is_admin == 0){
                unset($product['items'][$key]); 
                    }
                }
            }
        }
        $costdiffper =  $products[$key2]['subtotal'] - $cost;
        if($products[$key2]['quantity'] == 0){
            $products[$key2]['quantity'] = 1;
        }
        $costdiffper = $costdiffper / $products[$key2]['quantity'];  
        $products[$key2]['costdiffper'] = $costdiffper;   


        $gain =  $products[$key2]['cost'] - $checktest;
        // $gain = round($gain,2);
        $gain = $gain / $products[$key2]['quantity'];  
        // $gain = round($gain);     ///// per product tax
        $products[$key2]['difperitem'] = $gain;   /// setting tax per item
        if($user->is_admin == 0){
        if($check == 0){
            unset($products[$key2]); 
        }
    } 
    }
    // }
    // else{
    //     foreach($products as $key2 => $product){
    //         $products[$key2]['profitper'] = $user->profit;    //// current user profit percentage
    //     }
    // }
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
        $checkprice = 0;
        $retailprice = 0;
        foreach($product['items'] as $key3 => $item2){
               $checkprice += $item2['price']*$item2['quantity'];
               $retailprice += $item2['retail_price']*$item2['quantity'];
        }
        $checkprice = round($checkprice, 2);
        $diff = $products[$key2]['cost']  - $checkprice;
        $diff = round($diff, 2);
        if($products[$key2]['quantity'] > 0 ){ 
        $diff = $diff/$products[$key2]['quantity'];
        $diff = round($diff, 2);
        $totalItems = count($product['items']);

        $PerItemAdd = $products[$key2]['subtotal'] * 0.97- 0.30 - $retailprice;
        $PerItemAdd = $PerItemAdd / $products[$key2]['quantity'];
        $PerItemAdd = round($PerItemAdd, 2);

        foreach($product['items'] as $key => $item){
            if($key == $totalItems-1){
               Order::where('id','=',$products[$key2]['id'])->update(['updated_at' => now()]);
            }
                $username = explode(' ', $item['name'] , 3);
                $username = $username[0].' '.$username[1];
                $users  = User::where('name', 'LIKE', '%'.$username.'%')->get();
                if(count($users) > 0){
                if($users[0]['profit'] !== null){

                $profitePer  = $users[0]['profit'];  
                $orderdate = explode('-',$products[$key2]['odate']);

                $PreCliData = Profit::where('client_id' ,'=', $users[0]['name'])->whereYear('month' ,'=', $orderdate[0])->whereMonth('month' ,'=', $orderdate[1])->get();
                $maincost = $product['items'][$key]['price']*$product['items'][$key]['quantity']+$product['items'][$key]['quantity']*$diff;
                $retail_price = $product['items'][$key]['retail_price']*$product['items'][$key]['quantity']+$product['items'][$key]['quantity']*$PerItemAdd;
                $retail_price = round($retail_price, 2);
                $maincost = round($maincost, 2);
                $netprofit = $retail_price - $maincost;
                $netprofit = round($netprofit, 2);
                if (count($PreCliData) == 0 || $PreCliData === null) {   ////  no previous record found for current month
                    Profit::create([
                        'client_id' => $users[0]['name'],
                        'month' => $products[$key2]['odate'],
                        'items' =>  $item['name'],
                        'retail' => $retail_price,
                        'cost' => $maincost,
                        'profit' => round($netprofit*$users[0]['profit'], 2)
                    ]);
              }  
             else{
                // $maincost = $product['items'][$key]['price']*$product['items'][$key]['quantity']+$product['items'][$key]['quantity']*$diff;
                $match2 = [
                    'items' => $PreCliData[0]['items'].' , '.$item['name'],
                    'retail' => round($PreCliData[0]['retail']+$retail_price, 2),
                    'cost' => round($PreCliData[0]['cost']+$maincost, 2),
                    'profit' => round($PreCliData[0]['profit']+$netprofit*$users[0]['profit'], 2)
                ];
                // dd($PreCliData[0]['id']);
                Profit::where('id', '=' ,$PreCliData[0]['id'])->update($match2);
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
    $users = User::where('is_admin','=','0')->paginate(50);
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
        }
        else{
            $imageName = '';
        }
    $updateDetails = [
        'name' => $request->name,
        'email' => $request->email,
        'type' => $request->type,
        'phone' => $request->phone,
        'profit' => $request->profit,
        'image' => $imageName,
    ];
    User::where('id', '=', $request->id)->update($updateDetails);
    return redirect('/admin/vendors')->with('status', 'You record has been updated');
}

public function delete(Request $request){
        User::where('id', '=', $request->id)->delete();
    return redirect()->back()->with('status', 'You record has been deleted successfully');

}
}
