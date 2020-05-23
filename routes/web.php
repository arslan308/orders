<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return redirect('admin/home');
});
Route::get('/home', function () {
    return redirect('admin/home');
});

Auth::routes();

Route::get('admin/home', 'HomeController@index')->name('home');
Route::get('/verify', 'ShopifyController@index')->name('verify');
Route::get('/authenticate', 'ShopifyController@redirect');

Route::group(['prefix'=>'admin','as'=>'admin.'], function(){
    Route::get('/orders', 'ProductController@index')->name('orders');
    Route::get('/orders/get', 'ProductController@get')->name('getproducts');
});

