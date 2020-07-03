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
    return redirect('admin/login');
});

Auth::routes();

Route::get('admin/home', 'HomeController@index')->name('home');
Route::get('/verify', 'ShopifyController@index')->name('verify');
Route::get('/authenticate', 'ShopifyController@redirect');

Route::group(['prefix'=>'admin','as'=>'admin.'], function(){
    Route::get('/orders', 'ProductController@index')->name('orders');
    Route::get('/register', 'ProductController@register')->name('register');
    Route::get('/vendors', 'ProductController@vendors')->name('vendors');    
    Route::get('/vendors/{id}', 'ProductController@vendoredit')->name('vendoredit');      
    Route::post('/vendor/update', 'ProductController@update')->name('update');  
    Route::post('/vendor/delete/{id}', 'ProductController@delete')->name('delete');      
    Route::get('/profit', 'ProductController@profit')->name('profit');    
    Route::get('/getprofit', 'ProductController@getprofit')->name('getprofit');   
    Route::get('/getprofit/get/{range}', 'ProductController@getprofitget')->name('getprofitget');    
    Route::get('/orders/get/{range}', 'ProductController@get')->name('getproducts');

});

Route::group(['prefix'=>'admin','as'=>'admin.'], function(){
    Route::get('/account', 'AccountController@account')->name('settings');
    Route::post('/account/update2', 'AccountController@update')->name('update'); 

});