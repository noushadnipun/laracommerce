<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function index($id = null){
        if($id){
            $category = ProductCategory::find($id);
        } else {
            $category = '';
        }
        $getCategory = ProductCategory::orderBy('created_at', 'desc')->get();
        return view('admin.product.product-category', compact('getCategory', 'category'));
    }
    //store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:product_categories,slug',
        ]);
        $data = new ProductCategory();
        $data->name = $request->name;
        $data->slug = $request->slug;
        $data->description = $request->description;
        $data->parent_id = $request->parent_id;
        $data->image = $request->catimg_id;
        $data->visibility = $request->visibility;
        $data->save();
        return redirect()->back()->with('success', 'Added Successfully');
    }
    //update
    public function update(Request $request)
    {
        
        $data = ProductCategory::find($request->id);
        $data->name = $request->name;
        $data->slug = $request->slug;
        $data->description = $request->description;
        $data->parent_id = $request->parent_id;
        $data->image = $request->catimg_id;
        $data->visibility = $request->visibility;
        $data->save();
        return redirect()->back()->with('success', 'Edited Successfully');
    }

     public function destroy($id)
    {
        $data = ProductCategory::find($id);
        //Chnage all child id which under this id
        $changeParent = ProductCategory::where('parent_id', $id)->get();
        if(!empty($changeParent)){
            foreach($changeParent as $change){
                $upParent = ProductCategory::where('parent_id', $id)->first();
                $upParent->parent_id = null;
                $upParent->save();
            }
        }

        $data->delete();
        return redirect()->back()->with('delete', 'Deleted Successfully');
    }


    //Ajax get Category 
    public function ajaxCategory($id)
    {
        if($id == '0'){
            $parent_id = null;
        } else {
            $parent_id = $id;
        }
         $getCategory = ProductCategory::where('parent_id', $parent_id)->get();

         if(count($getCategory) == 0){
            return 'none';
         } else {
            return $getCategory;
         }
    }

}
