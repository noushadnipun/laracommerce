<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductOrder;
use App\Models\ProductOrderDetails;
class ProductOrderController extends Controller
{
    public function index(Request $request){
        if($request->payment_status){
            $getAllOrder = ProductOrder::with('user', 'orderDetails')->where('payment_status', $request->payment_status)->orderBy('created_at', 'DESC')->paginate('20');
        }elseif($request->delivery_status){
            $getAllOrder = ProductOrder::with('user', 'orderDetails')->where('delivery_status', $request->delivery_status)->orderBy('created_at', 'DESC')->paginate('20');
        }elseif($request->order_code){
            $getAllOrder = ProductOrder::with('user', 'orderDetails')->where('order_code', $request->order_code)->orderBy('created_at', 'DESC')->paginate('20');
        }else{
            $getAllOrder = ProductOrder::with('user', 'orderDetails')->orderBy('created_at', 'DESC')->paginate('20');
        }
        //return $getAllOrder;
        return view ('admin.product.order.index', compact('getAllOrder'));
    }

    //View Order By
    public function view($id){
        $order = ProductOrder::with('user', 'orderDetails')->where('id', $id)->orderBy('created_at', 'DESC')->first();
        //return $order;
        return view('admin.product.order.view', compact('order'));
    }

    //Order Delete
    public function destroy($id){
        $order = ProductOrder::find($id);
        $order->delete();
        return redirect()->back()->with('delete', 'Deleted Successfully.');
    }


    //Payment Status Change
    public function changePaymentStatus(Request $request)
    {
        $order = ProductOrder::find($request->id);
        $order->payment_status = $request->payment_status;
        $order->save();
        return redirect()->back()->with('success', 'Updated Successfully.');
    }

    //Delivery Status Change
    public function changeDeliveryStatus(Request $request)
    {
        $order = ProductOrder::find($request->id);
        $order->delivery_status = $request->delivery_status;
        $order->save();
        return redirect()->back()->with('success', 'Updated Successfully.');
    }
}
