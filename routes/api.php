<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->post('/callback/{order_id}',['uses'=> 'ControllerOrders@callback']);
Route::middleware('api')->post('/create_api_token','ApiTokenController@update');
Route::middleware('api')->post('/order/update/{order_id}',['uses'=> 'ControllerOrders@update_order']);
Route::middleware('api')->get('/product/{sku}',['uses'=> 'ControllerProduct@get_product_system']);
Route::middleware('api')->post('/order/product/{order_id}/{key}/{action}',['uses'=> 'ControllerOrders@update_product_order']);
