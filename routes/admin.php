<?php
//'middleware' => ['admin']
Route::group([
    'prefix'=> 'admin/', 
    'namespace'=> 'App\Http\Controllers\Admin', 
    'as' => 'admin_', 
    'middleware' => ['auth', 'role:admin']
], function(){
    
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('adminMenu','DashboardController@menu')->name('adminMenu');
    
    //Menu Management
    Route::resource('menu', 'MenuController');
    Route::get('menu/{id}/items', 'MenuController@items')->name('menu.items');
    Route::post('menu/{id}/items', 'MenuController@storeItem')->name('menu.items.store');
    Route::put('menu/{menuId}/items/{itemId}', 'MenuController@updateItem')->name('menu.items.update');
    Route::delete('menu/{menuId}/items/{itemId}', 'MenuController@deleteItem')->name('menu.items.delete');
    Route::post('menu/{id}/update-order', 'MenuController@updateOrder')->name('menu.update-order');
    
    //Media
    Route::get('media/all', 'MediaController@index')->name('media_index');
    Route::post('media/store', 'MediaController@store')->name('media_store');
    Route::post('media/store/noajax', 'MediaController@storeMedia')->name('media_store_noajax');
    Route::get('media/get', 'MediaController@getMedia')->name('media_get');
    Route::get('media/delete/{id}', 'MediaController@destroy')->name('media_delete');

    //Products
    Route::get('product/all', 'ProductController@index')->name('product_index');
    Route::get('product/create', 'ProductController@form')->name('product_create');
    Route::post('product/store', 'ProductController@store')->name('product_store');
    Route::post('product/update', 'ProductController@update')->name('product_update');
    Route::get('product-category/{id}/product', 'ProductController@index')->name('category_by_product');
    Route::get('product-brand/{brandId}/product', 'ProductController@index')->name('brand_by_product');
    
    //Product Import (must be before product/{id} route)
    Route::get('product/import', 'ProductImportController@index')->name('product_import');
    Route::get('product/import/template', 'ProductImportController@downloadTemplate')->name('product_import_template');
    Route::post('product/import', 'ProductImportController@import')->name('product_import_store');
    Route::get('product/import/status/{id}', 'ProductImportController@getStatus')->name('product_import_status');
    Route::get('product/import/jobs', 'ProductImportController@getJobs')->name('product_import_jobs');
    
    //Inventory Management
    Route::get('inventory', 'InventoryController@index')->name('inventory_index');
    Route::get('inventory/{id}', 'InventoryController@show')->name('inventory_show');
    Route::put('inventory/{id}', 'InventoryController@update')->name('inventory_update');
    Route::post('inventory/{id}/add-stock', 'InventoryController@addStock')->name('inventory_add_stock');
    Route::post('inventory/{id}/remove-stock', 'InventoryController@removeStock')->name('inventory_remove_stock');
    Route::get('inventory/low-stock', 'InventoryController@lowStock')->name('inventory_low_stock');
    Route::get('inventory/out-of-stock', 'InventoryController@outOfStock')->name('inventory_out_of_stock');
    
    //Product Edit/Delete (must be after import routes)
    Route::get('product/{id}', 'ProductController@form')->name('product_edit');
    Route::get('product/{id}/delete', 'ProductController@destroy')->name('product_delete');

    //Product category
    Route::get('product-category/all', 'ProductCategoryController@index')->name('product_category_index');
    Route::post('product-category/store', 'ProductCategoryController@store')->name('product_category_store');
    Route::get('product-category/{id}', 'ProductCategoryController@index')->name('product_category_edit');
    Route::post('product-category/update', 'ProductCategoryController@update')->name('product_category_update');
    Route::get('product-category/{id}/delete', 'ProductCategoryController@destroy')->name('product_category_delete');
    Route::get('ajaxget/product-category/{id}', 'ProductCategoryController@ajaxCategory')->name('product_category_ajax_get');

    //Product Order
    Route::get('manage/order', 'ProductOrderController@index')->name('product_order_index');
    Route::get('order/view/{id}', 'ProductOrderController@view')->name('product_order_view');
    Route::get('order/delete/{id}', 'ProductOrderController@destroy')->name('product_order_delete');
    Route::get('manage/order/filter', 'ProductOrderController@index')->name('product_order_filter');

    //Status Change
    Route::post('order/payment-status/', 'ProductOrderController@changePaymentStatus')->name('order_change_payment_status');
    Route::post('order/delivery-status/', 'ProductOrderController@changeDeliveryStatus')->name('order_change_delivery_status');
    Route::post('order/update-status/', 'ProductOrderController@updateStatus')->name('order_update_status');
    Route::post('order/partial-cancel/', 'ProductOrderController@partialCancel')->name('order_partial_cancel');
    Route::post('order/add-notes/', 'ProductOrderController@addNotes')->name('order_add_notes');
    Route::post('order/send-notification/', 'ProductOrderController@sendNotification')->name('order_send_notification');
    Route::get('order/stats/', 'ProductOrderController@getStats')->name('order_stats');

    //Brand
    Route::get('product-brand/all', 'ProductBrandController@index')->name('product_brand_index');
    Route::post('product-brand/store', 'ProductBrandController@store')->name('product_brand_store');
    
    //Reviews
    Route::get('reviews', 'ReviewController@index')->name('review_index');
    Route::get('reviews/{id}/edit', 'ReviewController@edit')->name('review_edit');
    Route::post('reviews/{id}/update', 'ReviewController@update')->name('review_update');
    Route::post('reviews/{id}/approve', 'ReviewController@approve')->name('review_approve');
    Route::post('reviews/{id}/reject', 'ReviewController@reject')->name('review_reject');
    Route::post('reviews/bulk-approve', 'ReviewController@bulkApprove')->name('review_bulk_approve');
    Route::post('reviews/bulk-reject', 'ReviewController@bulkReject')->name('review_bulk_reject');
    Route::delete('reviews/{id}', 'ReviewController@destroy')->name('review_delete');
    Route::get('reviews/stats', 'ReviewController@getStats')->name('review_stats');
    
    //Size Guide Routes
    Route::get('size-guides', 'SizeGuideController@index')->name('size_guide_index');
    Route::get('size-guides/create', 'SizeGuideController@create')->name('size_guide_create');
    Route::post('size-guides/store', 'SizeGuideController@store')->name('size_guide_store');
    Route::get('size-guides/{id}/edit', 'SizeGuideController@edit')->name('size_guide_edit');
    Route::post('size-guides/{id}/update', 'SizeGuideController@update')->name('size_guide_update');
    Route::delete('size-guides/{id}', 'SizeGuideController@destroy')->name('size_guide_delete');
    Route::post('size-guides/{id}/toggle-status', 'SizeGuideController@toggleStatus')->name('size_guide_toggle_status');
    Route::get('size-guides/get-default-chart', 'SizeGuideController@getDefaultChart')->name('size_guide_default_chart');
    
    //Statistics Routes
    Route::get('statistics', 'StatisticsController@index')->name('statistics_index');
    Route::get('statistics/product/{id}', 'StatisticsController@productStats')->name('statistics_product');
    Route::get('statistics/export', 'StatisticsController@export')->name('statistics_export');
    Route::get('statistics/api', 'StatisticsController@api')->name('statistics_api');
    
    Route::get('product-brand/{id}', 'ProductBrandController@index')->name('product_brand_edit');
    Route::post('product-brand/update', 'ProductBrandController@update')->name('product_brand_update');
    Route::get('product-brand/{id}/delete', 'ProductBrandController@destroy')->name('product_brand_delete');

    //Coupon
    Route::get('coupon/all', 'CouponController@index')->name('product_coupon_index');
    Route::post('coupon/store', 'CouponController@store')->name('product_coupon_store');
    Route::get('coupon/{id}', 'CouponController@index')->name('product_coupon_edit');
    Route::post('coupon/update', 'CouponController@update')->name('product_coupon_update');
    Route::get('coupon/{id}/delete', 'CouponController@destroy')->name('product_coupon_delete');

    //Attribute
    Route::get('attribute/all', 'AttributeController@index')->name('product_attribute_index');
    Route::post('attribute/store', 'AttributeController@store')->name('product_attribute_store');
    Route::get('attribute/{id}', 'AttributeController@index')->name('product_attribute_edit');
    Route::post('attribute/update', 'AttributeController@update')->name('product_attribute_update');
    Route::get('attribute/{id}/delete', 'AttributeController@destroy')->name('product_attribute_delete');

    //Attribute
    Route::get('attribute-value/all', 'AttributeController@values')->name('product_attribute_values_index');
    Route::post('attribute-value/store', 'AttributeController@valuestore')->name('product_attribute_value_store');
    Route::get('attribute-value/{id}', 'AttributeController@values')->name('product_attribute_value_edit');
    Route::post('attribute-value/update', 'AttributeController@valueupdate')->name('product_attribute_value_update');
    Route::get('attribute-value/{id}/delete', 'AttributeController@valuedestroy')->name('product_attribute_value_delete');




    /**
     * Dynamic Post Type && Term Taxonomy
     */
    //Post
    Route::get('term_type={type}/all', 'PostController@index')->name('term_type_index');
    Route::get('term_type={type}/create', 'PostController@form')->name('term_type_form'); 
    Route::post('term_type={type}/store', 'PostController@store')->name('term_type_store'); 
    Route::get('term_type={type}/edit/{id}', 'PostController@form')->name('term_type_edit'); 
    Route::post('term_type={type}/update', 'PostController@update')->name('term_type_update'); 
    Route::get('term_type={type}/delete/{id}', 'PostController@destroy')->name('term_type_delete');
    
    //Categories
    Route::get('term_type={type}/taxonomy={taxonomy}/all', 'CategoryController@index')->name('taxonomy_type_index');
    Route::post('term_type={type}/taxonomy={taxonomy}/store', 'CategoryController@store')->name('taxonomy_type_store'); 
    Route::get('term_type={type}/taxonomy={taxonomy}/edit/{id}', 'CategoryController@index')->name('taxonomy_type_edit'); 
    Route::post('term_type={type}/taxonomy={taxonomy}/update', 'CategoryController@update')->name('taxonomy_type_update'); 
    Route::get('term_type={type}/taxonomy={taxonomy}/delete/{id}', 'CategoryController@destroy')->name('taxonomy_type_delete');


    /* -------------------------------
    ----------- Settings -------------
    ----------------------------------*/

    // Frontend Settings
    Route::get('seetings/fronend/view', 'FrontendSettingsController@index')->name('frontend_settings_index');
    Route::post('seetings/fronend/update', 'FrontendSettingsController@update')->name('frontend_settings_update');
    //Store Settings
    Route::get('seetings/store/view', 'StoreSettingController@index')->name('product_store_settings_index');
    Route::post('seetings/store/update', 'StoreSettingController@update')->name('product_store_settings_update');
});

?>