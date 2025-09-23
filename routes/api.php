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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Cart API Routes - Commented out for now
// Route::prefix('cart')->group(function () {
//     Route::get('/', 'Api\CartController@index');
//     Route::post('/', 'Api\CartController@store');
//     Route::put('/{cartKey}', 'Api\CartController@update');
//     Route::delete('/{cartKey}', 'Api\CartController@destroy');
//     Route::delete('/', 'Api\CartController@clear');
//     Route::get('/count', 'Api\CartController@count');
//     Route::get('/summary', 'Api\CartController@summary');
//     Route::get('/validate', 'Api\CartController@validate');
// });

// Checkout API Routes - Commented out for now
// Route::prefix('checkout')->group(function () {
//     Route::get('/', 'Api\CheckoutController@index');
//     Route::post('/', 'Api\CheckoutController@store');
//     Route::post('/address', 'Api\CheckoutController@saveAddress');
//     Route::get('/order/{orderId}', 'Api\CheckoutController@orderSummary');
//     Route::get('/payment-methods', 'Api\CheckoutController@paymentMethods');
//     Route::get('/shipping-methods', 'Api\CheckoutController@shippingMethods');
// });

// Product API Routes (for frontend components) - Commented out for now
// Route::prefix('products')->group(function () {
//     Route::get('/', 'Api\ProductController@index');
//     Route::get('/{id}', 'Api\ProductController@show');
//     Route::get('/category/{categoryId}', 'Api\ProductController@byCategory');
//     Route::get('/search/{query}', 'Api\ProductController@search');
// });

// Menu API Routes (for dynamic menu) - Commented out for now
// Route::prefix('menu')->group(function () {
//     Route::get('/{location}', 'Api\MenuController@getMenu');
//     Route::get('/', 'Api\MenuController@getAllMenus');
// });