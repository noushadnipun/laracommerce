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


    //Single product Page Show
    public function singleProduct($slug){
        $product = Product::where('slug', $slug)->where('visibility', '1')->first();
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
        return view('frontend.product.product-modal', compact('product'));
    }

    //getPage
    public function page($slug)
    {
        $page = Post::where('slug', $slug)->first();
        return view('frontend.page', compact('page'));
    }
   
}
