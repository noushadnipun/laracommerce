<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductBrand;

use App\Models\Post;

class HomeController extends Controller
{
    //index
    public function index()
    {
        return view('frontend.index');
    }

    //Products listing page
    public function products(Request $request)
    {
        $query = Product::where('visibility', '1');
        
        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Filter by brand if provided
        if ($request->has('brand') && $request->brand) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }
        
        // Filter by price range if provided
        if ($request->has('min_price') && $request->min_price) {
            $query->where('regular_price', '>=', $request->min_price * 100); // Convert to cents
        }
        
        if ($request->has('max_price') && $request->max_price) {
            $query->where('regular_price', '<=', $request->max_price * 100); // Convert to cents
        }
        
        // Filter by search term
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('regular_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('regular_price', 'desc');
                break;
            case 'name':
                $query->orderBy('title', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $products = $query->with(['category', 'brand'])->paginate(20);
        
        // Get categories and brands for filters
        $categories = ProductCategory::where('visibility', '1')->get();
        $brands = ProductBrand::where('visibility', '1')->get();
        
        return view('frontend.product.products', compact('products', 'categories', 'brands'));
    }


    //Single product Page Show
    public function singleProduct($slug){
        $product = Product::with(['category', 'brand', 'sizeGuides'])->where('slug', $slug)->where('visibility', '1')->first();
        
        if ($product) {
            // Track product view
            $product->trackView();
        }
        
        return view('frontend.product.single-product', compact('product'));
    }

     //Single Product category
    public function singleProductCategory($slug){
        $getProductID = ProductCategory::where('slug', $slug)->first();
        $getProduct = Product::productByCatId($getProductID->id)->where('visibility', '1')->paginate('15');
        $categoryName = $getProductID->name;
        return view('frontend.product.product-category', compact('getProduct', 'categoryName'));
    }

     //Single Product category
    public function singleProductBrand($slug){
        $getBrandID = ProductBrand::where('slug', $slug)->first();
        $getProduct = Product::where('brand_id', $getBrandID->id)->where('visibility', '1')->paginate('15');
        $categoryName = $getBrandID->name;
        return view('frontend.product.product-category', compact('getProduct', 'categoryName'));
    }

    //Ajax request Product Quick View
    public function productQuickViewAjax($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'error' => 'Product not found',
                'message' => 'The requested product could not be found.'
            ], 404);
        }
        
        return view('frontend.product.product-modal', compact('product'));
    }

    //getPage
    public function page($slug)
    {
        $page = Post::where('slug', $slug)->first();
        return view('frontend.page', compact('page'));
    }


    //Price Range Filter
    public function filterByPrice(Request $request)
    {
        $explode = explode('-', $request->amount);
       
        $getProduct = Product::whereBetween('regular_price', [$explode[0], $explode[1]])->orWhereBetween('sale_price', [$explode[0], $explode[1]])->paginate('15');
        $categoryName = null;

        return view('frontend.product.product-category', compact('getProduct', 'categoryName'));
        
    }
    // Search
    public function search(Request $request)
    {
        $searchValue = $request->search;
        $category_id = $request->select;
       
        $getProduct = Product::where('title', 'LIKE', '%'.$searchValue.'%')
                            ->where('category_id', 'LIKE', '%'.$category_id.'%')
                                ->paginate('15');
        
        // if(!empty($category_id)){
        //    $getProduct->whereRaw("FIND_IN_SET($category_id, category_id)");
        // }
        $categoryName = $searchValue;

        return view('frontend.product.product-category', compact('getProduct', 'categoryName'));
        
    }
   
}
