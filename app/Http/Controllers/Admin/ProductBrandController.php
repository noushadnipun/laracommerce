<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductBrand;

class ProductBrandController extends Controller
{
     public function index($id = null){
        if($id){
            $brand = ProductBrand::find($id);
        } else {
            $brand = '';
        }
        $getBrand = ProductBrand::orderBy('created_at', 'desc')->get();
        return view('admin.product.product-brand', compact('getBrand', 'brand'));
    }
    //store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:product_brands,slug',
        ]);
        $data = new ProductBrand();
        $data->name = $request->name;
        $data->slug = $request->slug;
        $data->image = $request->brandimg_id;
        $data->visibility = $request->visibility;
        $data->save();
        return redirect()->back()->with('success', 'Added Successfully');
    }
    //update
    public function update(Request $request)
    {
       
        $data = ProductBrand::find($request->id);
        $data->name = $request->name;
        $data->slug = $request->slug;
        $data->image = $request->brandimg_id;
        $data->visibility = $request->visibility;
        $data->save();
        return redirect()->back()->with('success', 'Edited Successfully');
    }

    public function destroy($id)
    {
        $data = ProductBrand::find($id);
        $data->delete();
        return redirect()->back()->with('delete', 'Deleted Successfully');
    }
}
