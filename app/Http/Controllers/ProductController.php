<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZfrShopify\ShopifyClient;
use GuzzleHttp\Client;
use App\User;
use App\Shop;
use Yajra\Datatables\Datatables;
use Auth;

class ProductController extends Controller
{
    public function __construct(){
        $this->middleware('auth',['except' => 'singleget']);

    }
    public function index(){   
        return view('products.view');
    }

    public function get(){
        $shop = Shop::all();
        $api_key = "ae103121504821129123616e2e21516d";
        $shared_secret = $shop[0]->access_token;

        $shopifyClient = new ShopifyClient([
            'private_app'   => true,
            'api_key'       => $api_key,
            'password'    => $shared_secret,
            'shop'          => 'athletes-direct.myshopify.com',
            'version'       => '2020-04'
        ]);
        $products = $shopifyClient->getOrders(['limit'=>'250']);
        $user  = Auth::user();
        if($user->is_admin == 0){
        $num = $user->type;
        $int = (int)$num;
        $collproducts = $shopifyClient->getCollection(['id' => $int]);
        foreach($products as $key2 => $product){
            $check = 0;
            foreach($product['line_items'] as $key => $item){
                if(strpos($item['title'], $collproducts['title']) !== FALSE){
                    $check++;
                    }
                    else{
                    unset($product['line_items'][$key]); 
                    }
            }
            if($check == 0){
                unset($products[$key2]); 
            }
        }
    }
        return Datatables::of($products)
        ->rawColumns(['line_items','created_at'])
        ->make(true);
    }

}
