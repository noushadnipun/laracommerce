<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

 use App\Models\StoreSettings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       
    //Setting
    
        $storeID = StoreSettings::where('meta_name', 'ssl_store_id')->select('meta_value')->first();
        $storePass = StoreSettings::where('meta_name', 'ssl_store_password')->select('meta_value')->first();
        $connectFrom = StoreSettings::where('meta_name', 'ssl_sandbox_live')->select('meta_value')->first();
        if($connectFrom == 'live'){
            $getConnect = false;
            $appDomain = 'https://securepay.sslcommerz.com';
        } else {
            $getConnect = true;
            $appDomain = 'https://sandbox.sslcommerz.com';
        }
    }
}
