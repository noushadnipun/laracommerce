<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\MenuHelper as Menu;
use DB;

class DashboardController extends Controller
{
    //Index
    public function index()
    {
        return view('admin.index');
    }

    //Menu
    public function menu()
    {
        // Create admin menu
        Menu::make('admin', function() {
            Menu::add('Dashboard', route('admin_dashboard'), ['class' => 'nav-item'])
                ->add('Products', '#', ['class' => 'nav-item has-submenu'])
                    ->addSubmenu('All Products', route('admin_product_index'), ['class' => 'nav-item'])
                    ->addSubmenu('Add Product', route('admin_product_create'), ['class' => 'nav-item'])
                    ->addSubmenu('Categories', route('admin_product_category_index'), ['class' => 'nav-item'])
                    ->addSubmenu('Brands', route('admin_product_brand_index'), ['class' => 'nav-item'])
                ->add('Orders', route('admin_product_order_index'), ['class' => 'nav-item'])
                ->add('Media', route('admin_media_index'), ['class' => 'nav-item'])
                ->add('Settings', '#', ['class' => 'nav-item has-submenu'])
                    ->addSubmenu('Store Settings', route('admin_product_store_settings_index'), ['class' => 'nav-item'])
                    ->addSubmenu('Frontend Settings', route('admin_frontend_settings_index'), ['class' => 'nav-item'])
                ->add('Logout', route('logout'), ['class' => 'nav-item']);
        })->attributes(['class' => 'nav navbar-nav']);

        $menu = Menu::render('admin');
        $mscript = Menu::scripts();
        return view('admin.layouts.menu', compact('menu', 'mscript'));
    }
}
