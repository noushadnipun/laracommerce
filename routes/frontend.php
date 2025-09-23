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
     Route::get('customer/login', 'CustomerController@login')->name('customer_login');
     Route::get('customer/profile', 'CustomerController@dashboard')->name('customer_dashboard');
     Route::post('customer/profile/account-update', 'CustomerController@accountUpdate')->name('customer_account_update');
     Route::post('customer/address/update', 'CustomerController@AddressUpdate')->name('customer_address_update');

    //product
    Route::get('products', 'HomeController@products')->name('products');
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
     
          Route::post('success', 'CheckoutController@checkoutSuccess')->name('checkout_success')
          ->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
          Route::post('fail', 'CheckoutController@checkoutFailed')->name('checkout_failed')
          ->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
          Route::post('cancel', 'CheckoutController@checkoutCancel')->name('checkout_cancel')
          ->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
          Route::post('ipn', 'CheckoutController@ipn')->name('checkout_ipn')
          ->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
     


    //Ajax Request
    Route::get('product/quick-view/{id}', 'HomeController@productQuickViewAjax')->name('product_quick_view');
    Route::get('order/quick-view/{id}', 'customerController@orderQuickViewAjax')->name('order_quick_view');
    
    //Wishlist Routes
    Route::post('wishlist/add', 'WishlistController@add')->name('wishlist_add');
    Route::post('wishlist/remove', 'WishlistController@remove')->name('wishlist_remove');
    Route::post('wishlist/toggle', 'WishlistController@toggle')->name('wishlist_toggle');
    Route::get('wishlist/check', 'WishlistController@check')->name('wishlist_check');
    Route::get('my/wishlist', 'WishlistController@index')->name('wishlist_index');
    
    //Compare Routes
    Route::post('compare/add', 'CompareController@add')->name('compare_add');
    Route::post('compare/remove', 'CompareController@remove')->name('compare_remove');
    Route::get('compare/check', 'CompareController@check')->name('compare_check');
    Route::get('my/compare', 'CompareController@index')->name('compare_index');
    Route::post('compare/clear', 'CompareController@clear')->name('compare_clear');
    
    //Review Routes
    Route::post('review/store', 'ReviewController@store')->name('review_store');
    Route::get('review/product/{id}', 'ReviewController@getProductReviews')->name('review_product');
    Route::get('review/stats/{id}', 'ReviewController@getReviewStats')->name('review_stats');
    Route::get('review/can-review/{id}', 'ReviewController@canReview')->name('review_can_review');


     //Filter
     Route::get('filter/price', 'HomeController@filterByPrice')->name('filter_price');
     //Search
     Route::get('search/', 'HomeController@search')->name('search');




});


