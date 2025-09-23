<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductBrand;
use App\Models\Inventory;
use App\Models\StockMovement;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-products', ['only' => ['index']]);
        $this->middleware('permission:create-products', ['only' => ['form', 'store']]);
        $this->middleware('permission:edit-products', ['only' => ['form', 'update']]);
        $this->middleware('permission:delete-products', ['only' => ['destroy']]);
    }
    public function index($Id = null)
    {
        $query = Product::with(['category', 'brand']);
        
        if($Id && Request()->routeIs('admin_category_by_product', $Id)){
            $catName = ProductCategory::categoryName($Id);
            $query->whereRaw("FIND_IN_SET(?, category_id)", [$Id]);
        }elseif($Id && Request()->routeIs('admin_brand_by_product', $Id)){
            $catName = ProductBrand::brandName($Id);
            $query->where('brand_id', $Id);
        }else{
            $catName = '';
            
            // Apply filters
            if(request('search')){
                $search = request('search');
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
            
            if(request('category')){
                $query->whereRaw("FIND_IN_SET(?, category_id)", [request('category')]);
            }
            
            if(request('brand')){
                $query->where('brand_id', request('brand'));
            }
            
            if(request('visibility') !== null){
                $query->where('visibility', request('visibility'));
            }
            
            if(request('price_min')){
                $query->where('regular_price', '>=', request('price_min') * 100);
            }
            
            if(request('price_max')){
                $query->where('regular_price', '<=', request('price_max') * 100);
            }
            
            if(request('stock_status')){
                switch(request('stock_status')){
                    case 'in_stock':
                        $query->where('current_stock', '>', 0);
                        break;
                    case 'out_of_stock':
                        $query->where('current_stock', '=', 0);
                        break;
                    case 'low_stock':
                        $query->where('current_stock', '>', 0)->where('current_stock', '<=', 10);
                        break;
                }
            }
            
            if(request('image_status')){
                switch(request('image_status')){
                    case 'has_images':
                        $query->where(function($q) {
                            $q->whereNotNull('product_image')
                              ->orWhereNotNull('remote_images')
                              ->orWhereNotNull('featured_image');
                        });
                        break;
                    case 'no_images':
                        $query->where(function($q) {
                            $q->whereNull('product_image')
                              ->whereNull('remote_images')
                              ->whereNull('featured_image');
                        });
                        break;
                    case 'remote_only':
                        $query->whereNotNull('remote_images')
                              ->whereNull('product_image')
                              ->whereNull('featured_image');
                        break;
                    case 'local_only':
                        $query->where(function($q) {
                            $q->whereNotNull('product_image')
                              ->orWhereNotNull('featured_image');
                        })->whereNull('remote_images');
                        break;
                }
            }
        }
        
        $perPage = request('per_page', '15');
        $product = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return view('admin.product.index', compact('product', 'catName'));
    }

    public function form($id = null)
    {
        if($id){
            $product = Product::with(['inventory', 'category', 'brand'])->find($id);
            //return $product;
        } else {
            $product = '';
        }
        return view('admin.product.form', compact('product'));
    }

    //Store
    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
        ]);
        //return $request->galleryimg_id;
        $category = !empty($request->category_id) ? implode(",", $request->category_id) : '';
        $data = new Product();
        $data->user_id = '1';
        $data->category_id = $category;
        $data->brand_id = $request->brand_id;
        $data->title = $request->title;
        $data->short_description = $request->short_description;
        $data->description = $request->description;
        $data->specification = $request->specification;
        $data->slug = $request->slug;
        $data->code = $request->code;
        $data->regular_price = $request->regular_price;
        $data->sale_price = $request->sale_price;
        $data->purchase_price = $request->purchase_price;
        $data->attribute = $request['attribute'];
        $data->refundable = '0';
        $data->shipping_type = $request->shipping_type;
        $data->shipping_cost = $request->shipping_cost;
        // Stock is now managed in inventory table only
        // Stock is now managed in inventory table only
        $data->product_image = $request->galleryimg_id;
        $data->featured_image = $request->featuredimg_id;
        
        // Process remote images
        $remoteImages = [];
        if ($request->remote_images) {
            $urls = array_filter(array_map('trim', explode("\n", $request->remote_images)));
            $remoteImages = array_values($urls);
        }
        $data->remote_images = $remoteImages;
        
        $data->visibility = $request->visibility;
        $data->save();
        
        // Create inventory record
        $this->updateInventory($data, $request);

        return redirect()->route('admin_product_edit', $data->id)->with('success', 'Added Successfully');
    }

    //update
     public function update(Request $request){


        if($request->category_id){
            $category= implode(",", $request->category_id);
        } else {
            $category = '';
        }

        $data = Product::find($request->id);
        $data->user_id = '1';
        $data->category_id = $category;
        $data->brand_id = $request->brand_id;
        $data->title = $request->title;
        $data->short_description = $request->short_description;
        $data->description = $request->description;
        $data->specification = $request->specification;
        $data->slug = $request->slug;
        $data->code = $request->code;
        $data->regular_price = $request->regular_price;
        $data->sale_price = $request->sale_price;
        $data->purchase_price = $request->purchase_price;
        $data->attribute = $request['attribute'];
        $data->refundable = '0';
        $data->shipping_type = $request->shipping_type;
        $data->shipping_cost = $request->shipping_cost;
        // Stock is now managed in inventory table only
        // Stock is now managed in inventory table only
        $data->product_image = $request->galleryimg_id;
        $data->featured_image = $request->featuredimg_id;
        
        // Process remote images
        $remoteImages = [];
        if ($request->remote_images) {
            $urls = array_filter(array_map('trim', explode("\n", $request->remote_images)));
            $remoteImages = array_values($urls);
        }
        $data->remote_images = $remoteImages;
        
        $data->visibility = $request->visibility;
        $data->save();
        
        // Update inventory
        $this->updateInventory($data, $request);

        return redirect()->back()->with('success', 'Edited Successfully');
    }

     public function destroy($id)
    {
        $data = Product::find($id);
        $data->delete();
        return redirect()->back()->with('delete', 'Deleted Successfully');
    }
    
    /**
     * Update inventory for product
     */
    private function updateInventory($product, $request)
    {
        $inventory = $product->inventory;
        
        if (!$inventory) {
            // Create new inventory record
            $inventory = Inventory::create([
                'product_id' => $product->id,
                'current_stock' => $request->current_stock ?? 0,
                'total_stock' => $request->total_stock ?? 0,
                'reserved_stock' => 0,
                'low_stock_threshold' => 10,
                'unit_cost' => $request->purchase_price ?? 0,
                'total_value' => ($request->purchase_price ?? 0) * ($request->current_stock ?? 0)
            ]);
        } else {
            // Update existing inventory
            $previousStock = $inventory->current_stock;
            $newStock = $request->current_stock ?? 0;
            
            $inventory->update([
                'current_stock' => $newStock,
                'total_stock' => $request->total_stock ?? $inventory->total_stock,
                'unit_cost' => $request->purchase_price ?? $inventory->unit_cost,
                'total_value' => ($request->purchase_price ?? $inventory->unit_cost) * $newStock
            ]);
            
            // Log stock movement if changed
            if ($previousStock != $newStock) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'adjustment',
                    'quantity' => abs($newStock - $previousStock),
                    'previous_stock' => $previousStock,
                    'new_stock' => $newStock,
                    'reference_type' => 'product_update',
                    'notes' => 'Stock updated from product form',
                    'user_id' => auth()->id() ?? 1
                ]);
            }
        }
    }

}
