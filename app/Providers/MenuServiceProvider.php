<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\MenuHelper;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Create default menus and menu items
        try {
            MenuHelper::createDefaultMenus();
            MenuHelper::createDefaultMenuItems();
        } catch (\Exception $e) {
            // Ignore if tables don't exist yet
        }
    }
}
