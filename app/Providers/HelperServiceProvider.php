<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Share common data with all views
        View::composer('*', function ($view) {
            $view->with([
                'cartCount' => \App\Helpers\Cart\CartHelper::getCartCount(),
                'cartTotal' => \App\Helpers\Cart\CartHelper::getCartTotal(),
                'formattedCartTotal' => \App\Helpers\Cart\CartHelper::formatAmount(\App\Helpers\Cart\CartHelper::getCartTotal()),
            ]);
        });
    }
}