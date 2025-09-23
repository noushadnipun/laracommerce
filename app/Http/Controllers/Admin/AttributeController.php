<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;

class AttributeController extends Controller
{
    //Index
    public function index($id = ''){
        if($id){
            $attribute = ProductAttribute::find($id);
        } else {
            $attribute = '';
        }
        $getAttribute = ProductAttribute::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        $getAttributeValue = ProductAttributeValue::with('attribute')->orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('admin.product.attribute.index',compact('getAttribute', 'getAttributeValue', 'attribute'));
    }

    //Attribute Values Index
    public function values($id = ''){
        if($id){
            $attributevalue = ProductAttributeValue::find($id);
        } else {
            $attributevalue = '';
        }
        $getAttribute = ProductAttribute::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        $getAttributeValue = ProductAttributeValue::with('attribute')->orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('admin.product.attribute.values',compact('getAttribute', 'getAttributeValue', 'attributevalue'));
    }

    //Manage Single Attribute with Values
    public function manage($id = null){
        $getAttribute = ProductAttribute::with('values')->orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        $selectedAttribute = null;
        
        if($id){
            $selectedAttribute = ProductAttribute::with('values')->find($id);
        }
        
        return view('admin.product.attribute.manage', compact('getAttribute', 'selectedAttribute'));
    }

    //Store
    public function store(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'display_type' => 'required|string',
            'is_required' => 'nullable|boolean',
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
        ]);
        
        $data = new ProductAttribute();
        $data->name = $request->name;
        $data->type = $request->type;
        $data->display_type = $request->display_type;
        $data->is_required = $request->has('is_required') ? $request->boolean('is_required') : false;
        $data->description = $request->description;
        $data->sort_order = $request->sort_order ?? 0;
        $data->is_active = $request->has('is_active') ? $request->boolean('is_active') : false;
        $data->save();
        
        return redirect()->back()->with('success', 'Attribute added successfully');
    }

    //Update
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'display_type' => 'required|string',
            'is_required' => 'nullable|boolean',
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
        ]);
        
        $data = ProductAttribute::find($request->id);
        $data->name = $request->name;
        $data->type = $request->type;
        $data->display_type = $request->display_type;
        $data->is_required = $request->has('is_required') ? $request->boolean('is_required') : false;
        $data->description = $request->description;
        $data->sort_order = $request->sort_order ?? 0;
        $data->is_active = $request->has('is_active') ? $request->boolean('is_active') : false;
        $data->save();
        
        return redirect()->back()->with('success', 'Attribute updated successfully');
    }

    //Delete
    public function destroy($id)
    {
        $d = ProductAttribute::find($id);
        $d->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');
    }


    //Attribute Value 



    //Index
    public function valueindex($id = ''){
        if($id){
            $attributevalue = ProductAttributeValue::find($id);
        } else {
            $attributevalue = '';
        }
        $getAttributeValue = ProductAttributeValue::orderBy('created_at', 'desc')->get();
        $getAttribute = ProductAttribute::orderBy('created_at', 'desc')->get();
        return view('admin.product.attribute.index',compact('getAttributeValue', 'getAttribute', 'attributevalue'));
    }

    //Store
    public function valuestore(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:product_attributes,id',
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'image' => 'nullable|string',
            'sort_order' => 'integer|min:0',
        ]);
        
        $data = new ProductAttributeValue();
        $data->attribute_id = $request->attribute_id;
        $data->value = $request->value;
        $data->color_code = $request->color_code;
        $data->image = $request->image;
        $data->sort_order = $request->sort_order ?? 0;
        $data->is_active = $request->has('is_active') ? $request->boolean('is_active') : false;
        $data->save();
        
        return redirect()->back()->with('success', 'Attribute value added successfully');
    }

    //Update
    public function valueupdate(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:product_attributes,id',
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'image' => 'nullable|string',
            'sort_order' => 'integer|min:0',
        ]);
        
        $data = ProductAttributeValue::find($request->id);
        $data->attribute_id = $request->attribute_id;
        $data->value = $request->value;
        $data->color_code = $request->color_code;
        $data->image = $request->image;
        $data->sort_order = $request->sort_order ?? 0;
        $data->is_active = $request->has('is_active') ? $request->boolean('is_active') : false;
        $data->save();
        
        return redirect()->back()->with('success', 'Attribute value updated successfully');
    }

    //Delete
    public function valuedestroy($id)
    {
        $d = ProductAttributeValue::find($id);
        $d->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');
    }

}