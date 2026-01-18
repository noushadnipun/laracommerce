<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\MenuHelper;

class MenuController extends Controller
{
    public function createMenu()
    {
        // Create main navigation menu
        Menu::make('main', function() {
            Menu::add('Home', route('frontend_index'))
                ->add('Products', route('frontend_single_product_category', 'all'))
                ->add('About', route('frontend_page', 'about'))
                ->add('Contact', route('frontend_page', 'contact'));
        })->attributes(['class' => 'navbar-nav']);

        // Create admin menu
        Menu::make('admin', function() {
            Menu::add('Dashboard', route('admin_dashboard'))
                ->add('Products', '#', ['class' => 'has-submenu'])
                    ->addSubmenu('All Products', route('admin_product_index'))
                    ->addSubmenu('Add Product', route('admin_product_create'))
                    ->addSubmenu('Categories', route('admin_product_category_index'))
                ->add('Orders', route('admin_product_order_index'))
                ->add('Media', route('admin_media_index'))
                ->add('Settings', '#', ['class' => 'has-submenu'])
                    ->addSubmenu('Store Settings', route('admin_product_store_settings_index'))
                    ->addSubmenu('Frontend Settings', route('admin_frontend_settings_index'));
        })->attributes(['class' => 'admin-menu']);

        return view('menu.example');
    }
}













