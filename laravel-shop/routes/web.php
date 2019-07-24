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

//root
Route::redirect('/', '/products')->name('root');
//用户登录注册等路由
Auth::routes();
//需要登录后才能进行的操作
Route::group(['middleware' => ['auth']], function () {
    //用户收货地址
    Route::resource('user_addresses', 'UserAddressesController');
    //收藏及取消收藏商品，收藏商品列表
    Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
    Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');
    Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');
    //添加购物车
    Route::post('cart', 'CartController@add')->name('cart.add');
    Route::get('cart', 'CartController@index')->name('cart.index');
    Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');
    //提交订单
    Route::post('orders', 'OrdersController@store')->name('orders.store');
    Route::get('orders', 'OrdersController@index')->name('orders.index');
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');
});
//商品首页及详情页
Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('products/{product}', 'ProductsController@show')->name('products.show');

