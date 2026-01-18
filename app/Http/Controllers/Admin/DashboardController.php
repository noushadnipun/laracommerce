<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\MenuHelper as Menu;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\ProductOrder;
use App\Models\ProductCategory;
use App\Models\ProductBrand;
use DB;

class DashboardController extends Controller
{
    //Index
    public function index()
    {
        // Get low stock products
        $lowStockProducts = Product::whereHas('inventory', function($query) {
            $query->whereRaw('current_stock <= low_stock_threshold')
                  ->where('current_stock', '>', 0);
        })->with(['inventory', 'category', 'brand'])
          ->join('inventory', 'products.id', '=', 'inventory.product_id')
          ->orderBy('inventory.current_stock', 'asc')
          ->select('products.*')
          ->limit(10)
          ->get();

        // Get out of stock products
        $outOfStockProducts = Product::whereHas('inventory', function($query) {
            $query->where('current_stock', '<=', 0);
        })->with(['inventory', 'category', 'brand'])
          ->orderBy('products.title')
          ->limit(10)
          ->get();

        // Get inventory statistics
        $inventoryStats = [
            'total_products' => Product::count(),
            'products_with_inventory' => Product::whereHas('inventory')->count(),
            'low_stock_count' => Product::whereHas('inventory', function($query) {
                $query->whereRaw('current_stock <= low_stock_threshold')
                      ->where('current_stock', '>', 0);
            })->count(),
            'out_of_stock_count' => Product::whereHas('inventory', function($query) {
                $query->where('current_stock', '<=', 0);
            })->count(),
            'in_stock_count' => Product::whereHas('inventory', function($query) {
                $query->where('current_stock', '>', 0)
                      ->whereRaw('current_stock > low_stock_threshold');
            })->count(),
        ];

        return view('admin.index', compact('lowStockProducts', 'outOfStockProducts', 'inventoryStats'));
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
