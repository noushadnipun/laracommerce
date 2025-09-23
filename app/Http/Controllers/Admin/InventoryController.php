<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display inventory dashboard
     */
    public function index(Request $request)
    {
        $query = Product::with(['inventory', 'category', 'brand']);
        
        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }
        
        // Stock status filter
        if ($request->has('stock_status') && $request->stock_status) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->whereHas('inventory', function($q) {
                        $q->where('current_stock', '>', 0);
                    });
                    break;
                case 'low_stock':
                    $query->whereHas('inventory', function($q) {
                        $q->whereRaw('current_stock <= low_stock_threshold');
                    });
                    break;
                case 'out_of_stock':
                    $query->whereHas('inventory', function($q) {
                        $q->where('current_stock', '<=', 0);
                    });
                    break;
            }
        }
        
        // Category filter
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Brand filter
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }
        
        $products = $query->orderBy('title')->paginate(20);
        
        // Get statistics
        $stats = $this->getInventoryStats();
        
        // Get categories and brands for filters
        $categories = \App\Models\ProductCategory::orderBy('name')->get();
        $brands = \App\Models\ProductBrand::orderBy('name')->get();
        
        return view('admin.inventory.index', compact('products', 'stats', 'categories', 'brands'));
    }
    
    /**
     * Show individual product inventory
     */
    public function show($id)
    {
        $product = Product::with(['inventory', 'stockMovements.user', 'category', 'brand'])->findOrFail($id);
        
        // Get recent stock movements
        $stockMovements = $product->stockMovements()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.inventory.show', compact('product', 'stockMovements'));
    }
    
    /**
     * Update inventory
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'current_stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255'
        ]);
        
        $product = Product::findOrFail($id);
        $inventory = $product->inventory;
        
        if (!$inventory) {
            $inventory = Inventory::create([
                'product_id' => $product->id,
                'current_stock' => $request->current_stock,
                'total_stock' => $request->current_stock,
                'low_stock_threshold' => $request->low_stock_threshold,
                'unit_cost' => $request->unit_cost ?? 0,
                'total_value' => ($request->unit_cost ?? 0) * $request->current_stock
            ]);
        } else {
            $previousStock = $inventory->current_stock;
            $newStock = $request->current_stock;
            
            $inventory->update([
                'current_stock' => $newStock,
                'total_stock' => $inventory->total_stock + ($newStock - $previousStock),
                'low_stock_threshold' => $request->low_stock_threshold,
                'unit_cost' => $request->unit_cost ?? $inventory->unit_cost,
                'total_value' => ($request->unit_cost ?? $inventory->unit_cost) * $newStock
            ]);
            
            // Log stock movement if changed
            if ($previousStock != $newStock) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'adjustment',
                    'quantity' => abs($newStock - $previousStock),
                    'previous_stock' => $previousStock,
                    'new_stock' => $newStock,
                    'reference_type' => 'manual_adjustment',
                    'notes' => $request->notes ?? 'Manual stock adjustment',
                    'user_id' => auth()->id()
                ]);
            }
        }
        
        // Update product table
        $product->update([
            'current_stock' => $inventory->current_stock,
            'total_stock' => $inventory->total_stock
        ]);
        
        return back()->with('success', 'Inventory updated successfully!');
    }
    
    /**
     * Add stock
     */
    public function addStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255'
        ]);
        
        $product = Product::findOrFail($id);
        $inventory = $product->inventory;
        
        if (!$inventory) {
            $inventory = Inventory::create([
                'product_id' => $product->id,
                'current_stock' => $request->quantity,
                'total_stock' => $request->quantity,
                'low_stock_threshold' => 10,
                'unit_cost' => 0,
                'total_value' => 0
            ]);
        } else {
            $inventory->updateStock('in', $request->quantity, $request->notes ?? 'Stock added manually', 'manual_addition');
        }
        
        // Update product table
        $product->update([
            'current_stock' => $inventory->current_stock,
            'total_stock' => $inventory->total_stock
        ]);
        
        return back()->with('success', "Added {$request->quantity} units to stock!");
    }
    
    /**
     * Remove stock
     */
    public function removeStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255'
        ]);
        
        $product = Product::findOrFail($id);
        $inventory = $product->inventory;
        
        if (!$inventory || $inventory->current_stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock!');
        }
        
        $inventory->updateStock('out', $request->quantity, $request->notes ?? 'Stock removed manually', 'manual_removal');
        
        // Update product table
        $product->update([
            'current_stock' => $inventory->current_stock,
            'total_stock' => $inventory->total_stock
        ]);
        
        return back()->with('success', "Removed {$request->quantity} units from stock!");
    }
    
    /**
     * Get inventory statistics
     */
    private function getInventoryStats()
    {
        $totalProducts = Product::count();
        $productsWithInventory = Product::whereHas('inventory')->count();
        $inStockProducts = Product::whereHas('inventory', function($q) {
            $q->where('current_stock', '>', 0);
        })->count();
        $lowStockProducts = Product::whereHas('inventory', function($q) {
            $q->whereRaw('current_stock <= low_stock_threshold');
        })->count();
        $outOfStockProducts = Product::whereHas('inventory', function($q) {
            $q->where('current_stock', '<=', 0);
        })->count();
        
        $totalStockValue = Inventory::sum('total_value');
        $totalCurrentStock = Inventory::sum('current_stock');
        
        return [
            'total_products' => $totalProducts,
            'products_with_inventory' => $productsWithInventory,
            'in_stock_products' => $inStockProducts,
            'low_stock_products' => $lowStockProducts,
            'out_of_stock_products' => $outOfStockProducts,
            'total_stock_value' => $totalStockValue,
            'total_current_stock' => $totalCurrentStock
        ];
    }
    
    /**
     * Get low stock products
     */
    public function lowStock()
    {
        $products = Product::whereHas('inventory', function($q) {
            $q->whereRaw('current_stock <= low_stock_threshold');
        })->with(['inventory', 'category', 'brand'])->paginate(20);
        
        return view('admin.inventory.low-stock', compact('products'));
    }
    
    /**
     * Get out of stock products
     */
    public function outOfStock()
    {
        $products = Product::whereHas('inventory', function($q) {
            $q->where('current_stock', '<=', 0);
        })->with(['inventory', 'category', 'brand'])->paginate(20);
        
        return view('admin.inventory.out-of-stock', compact('products'));
    }
}