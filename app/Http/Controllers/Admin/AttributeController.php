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
        $getAttribute = ProductAttribute::orderBy('created_at', 'desc')->get();
        $getAttributeValue = ProductAttributeValue::orderBy('created_at', 'desc')->get();
        return view('admin.product.attribute.index',compact('getAttribute', 'getAttributeValue', 'attribute'));
    }

    //Store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $data = new ProductAttribute();
        $data->name = $request->name;
        $data->save();
        return redirect()->back()->with('success', 'Added Successfully');
    }

    //Update
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $data = ProductAttribute::find($request->id);
        $data->name = $request->name;
        $data->save();
        return redirect()->back()->with('success', 'Added Successfully');
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
            'attributes_id' => 'required',
            'value' => 'required',
        ]);
        $data = new ProductAttributeValue();
        $data->attributes_id = $request->attributes_id;
        $data->value = $request->value;
        $data->save();
        return redirect()->back()->with('success', 'Added Successfully');
    }

    //Update
    public function valueupdate(Request $request)
    {
        $request->validate([
            'attributes_id' => 'required',
            'value' => 'required',
        ]);
        $data = ProductAttributeValue::find($request->id);
        $data->attributes_id = $request->attributes_id;
        $data->value = $request->value;
        $data->save();
        return redirect()->back()->with('success', 'Added Successfully');
    }

    //Delete
    public function valuedestroy($id)
    {
        $d = ProductAttributeValue::find($id);
        $d->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');
    }

}