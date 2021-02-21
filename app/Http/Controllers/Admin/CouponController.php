<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProductCoupon;
class CouponController extends Controller
{
    //Index
    public function index($id = ''){
        if($id){
            $coupon = ProductCoupon::find($id);
        } else {
            $coupon = '';
        }
        $getCoupon = ProductCoupon::orderBy('created_at', 'desc')->get();
        return view('admin.product.coupon.index',compact('getCoupon', 'coupon'));
    }

    //Store
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'amount' => 'required',
        ]);
        $data = new ProductCoupon();
        $data->code = $request->code;
        $data->type = $request->type;
        $data->amount = $request->amount;
        $data->status = $request->status;
        $data->save();
        return redirect()->back()->with('success', 'Added Successfully');
    }

    //Update
    public function update(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'amount' => 'required',
        ]);
        $data = ProductCoupon::find($request->id);
        $data->code = $request->code;
        $data->type = $request->type;
        $data->amount = $request->amount;
        $data->status = $request->status;
        $data->save();
        return redirect()->back()->with('success', 'Added Successfully');
    }

    //Delete
    public function destroy($id)
    {
        # code...
    }
}
