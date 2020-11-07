<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use ZfrShopify\OAuth\AuthorizationRedirectResponse;
use ZfrShopify\Exception\InvalidRequestException;
use ZfrShopify\Validator\RequestValidator;
use ZfrShopify\ShopifyClient;
use GuzzleHttp\Client;
use ZfrShopify\OAuth\TokenExchanger;
use App\User;
use App\Shop;
use App\RoleUser;
use App\ShopifyProducts;
use Auth;

class ShopifyController extends Controller
{
        public function index(Request $request){
     
            $apiKey         = '2cedddf03d8c528fb2aab37fdf9f069e'; 
            $shopDomain     = $request->input('shop'); 
            $scopes         = ['write_orders', 'write_products', 'write_themes', 'write_script_tags', 'write_content'];
            $redirectionUri = 'https://fanarchpartners.com/authenticate'; 
            $nonce          = 'strong_nonce';

            $response = new AuthorizationRedirectResponse($apiKey, $shopDomain, $scopes, $redirectionUri, $nonce);
            return redirect($response->getHeader('location')[0]);
        } 

        public function redirect(Request $request){
            if (Shop::where('domain', '=', $_GET['shop'])->exists()) {
            $shop =  Shop::where('domain', '=', $_GET['shop'])->first();
            $user = User::where('id', '=', $shop['user_id'])->first();
            Auth::login($user);
            $redirect = 'admin/home';
            return redirect($redirect);
            }
            else{
            // Set variables for our request
            $api_key = "2cedddf03d8c528fb2aab37fdf9f069e";
            $shared_secret = "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";
            $params = $_GET; // Retrieve all request parameters
            $hmac = $_GET['hmac']; // Retrieve HMAC request parameter
            $params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
            ksort($params); // Sort params lexographically

            // Compute SHA256 digest
            $computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);

            // Use hmac data to check that the response is from Shopify or not
            if (hash_equals($hmac, $computed_hmac)) {

            $shopDomain     = $_GET['shop'];
            $scopes         = ['write_orders', 'write_products', 'write_themes', 'write_script_tags', 'write_content'];
            $code           = $params['code'];
            $tokenExchanger = new TokenExchanger(new Client());
            $accessToken    = $tokenExchanger->exchangeCodeForToken($api_key, $shared_secret, $shopDomain,$scopes, $code);
            $shopifyClient = new ShopifyClient([
                'private_app'   => true,
                'api_key'       => $api_key,
                'password'  => $accessToken,
                'shop'          => $shopDomain,
                'version'       => '2020-04'
            ]);
            $shopDomain2 = $shopifyClient->getShop();
            $newuser = User::create([
                'name' => $shopDomain2['name'],
                'email' => $shopDomain2['email'],
                'is_admin' => true,
                'password' => Hash::make('password123'), 
            ]);
            $mainshop = Shop::create([
                'user_id' => $newuser['id'],
                'domain' => $shopDomain2['domain'], 
                'access_token' => $accessToken,
            ]);
            $user = User::where('email', '=', $shopDomain2['email'])->first();
            Auth::login($user); 
            $redirect = 'admin/home';
            return redirect($redirect);
            } else {
                dd('Something went wrong please try again');
            }
        }
    }



}
