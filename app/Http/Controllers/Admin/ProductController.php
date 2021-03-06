<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductBrand;

class ProductController extends Controller
{
    public function index($Id = null)
    {
        if($Id && Request()->routeIs('admin_category_by_product', $Id)){
            $catName = ProductCategory::categoryName($Id);
            $product = Product::orderBy('created_at', 'desc')->whereRaw("FIND_IN_SET($Id , category_id)")->paginate('20');

        }elseif($Id && Request()->routeIs('admin_brand_by_product', $Id)){
            $catName = ProductBrand::brandName($Id);
            $product = Product::orderBy('created_at', 'desc')->where('brand_id',$Id)->paginate('20');
        }else{
            $catName = '';
            $product = Product::orderBy('created_at', 'desc')->paginate('20');
        }
        return view('admin.product.index', compact('product', 'catName'));
    }

    public function form($id = null)
    {
        if($id){
            $product = Product::find($id);
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
        $data->total_stock = $request->total_stock;
        $data->current_stock = $request->current_stock;
        $data->product_image = $request->galleryimg_id;
        $data->featured_image = $request->featuredimg_id;
        $data->visibility = $request->visibility;
        $data->save();

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
        $data->total_stock = $request->total_stock;
        $data->current_stock = $request->current_stock;
        $data->product_image = $request->galleryimg_id;
        $data->featured_image = $request->featuredimg_id;
        $data->visibility = $request->visibility;
        $data->save();

        return redirect()->back()->with('success', 'Edited Successfully');
    }

     public function destroy($id)
    {
        $data = Product::find($id);
        $data->delete();
        return redirect()->back()->with('delete', 'Deleted Successfully');
    }

}
