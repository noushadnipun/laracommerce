<?php

Route::group([
     'prefix'=> '/', 
     'namespace'=> 'App\Http\Controllers\Frontend', 
     'as' => 'frontend_',
     //'middleware' => ['auth', 'customer']
], function(){

     Route::get('/', 'HomeController@index')->name('index');
     Route::get('page/{slug}', 'HomeController@page')->name('page');

     //customer
     Route::get('customer/login', 'customerController@login')->name('customer_login');
     Route::get('customer/profile', 'customerController@dashboard')->name('customer_dashboard');
     Route::post('customer/profile/account-update', 'customerController@accountUpdate')->name('customer_account_update');
     Route::post('customer/address/update', 'customerController@AddressUpdate')->name('customer_address_update');

     //product
     Route::get('products/{slug}', 'HomeController@singleProduct')->name('single_product');
     Route::get('product/category/{slug}', 'HomeController@singleProductCategory')->name('single_product_category');
     Route::get('product/brand/{slug}', 'HomeController@singleProductBrand')->name('single_product_brand');

     //cart
     Route::get('my/cart/', 'CartController@index')->name('cart_index');
     Route::post('cart/store', 'CartController@store')->name('cart_store');
     Route::post('update-cart', 'CartController@update')->name('cart_update');
     Route::post('update-cart-all', 'CartController@multipleUpdate')->name('cart_update_multiple');
     Route::delete('remove-from-cart', 'CartController@remove')->name('cart_remove');

     //Coupon Apply
     Route::post('apply/coupon', 'CartController@couponApply')->name('apply_coupon');
     Route::get('apply/coupon/remove', 'CartController@couponApplyDestroy')->name('apply_coupon_remove');

     //checkout 
     Route::get('checkout/', 'CheckoutController@index')->name('checkout_index');
     Route::post('checkout/done', 'CheckoutController@checkout')->name('checkout_done');
     Route::post('success', 'CheckoutController@checkoutSuccess')->name('checkout_success');
     Route::post('fail', 'CheckoutController@checkoutFailed')->name('checkout_failed');
     Route::post('cancel', 'CheckoutController@checkoutCancel')->name('checkout_cancel');
     Route::post('ipn', 'CheckoutController@ipn')->name('checkout_ipn');


     //Ajax Request
     Route::get('product/quick-view/{id}', 'HomeController@productQuickViewAjax')->name('product_quick_view');
     Route::get('order/quick-view/{id}', 'customerController@orderQuickViewAjax')->name('order_quick_view');


     //Filter
     Route::get('filter/price', 'HomeController@filterByPrice')->name('filter_price');
     //Search
     Route::get('search/', 'HomeController@search')->name('search');




});


