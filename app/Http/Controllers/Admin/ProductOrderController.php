<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductOrder;
use App\Models\ProductOrderDetails;
use App\Models\OrderStatusHistory;
use App\Models\OrderNotification;
use App\Models\Inventory;
use App\Models\StockMovement;
use App\Helpers\Order\OrderHelper;
use Auth;
use DB;

class ProductOrderController extends Controller
{
    protected function adjustStock(int $productId, int $qty, string $direction, int $orderId, string $note = ''): void
    {
        $qty = max(0, $qty);
        if ($qty === 0) return;
        $inv = Inventory::where('product_id', $productId)->lockForUpdate()->first();
        if ($inv) {
            if ($direction === 'out') {
                $inv->current_stock = max(0, ((int)$inv->current_stock) - $qty);
            } else {
                $inv->current_stock = ((int)$inv->current_stock) + $qty;
            }
            $inv->save();
            try {
                if (class_exists(StockMovement::class)) {
                    StockMovement::create([
                        'product_id' => $productId,
                        'quantity' => $qty,
                        'type' => $direction === 'out' ? 'order_deduct_admin' : 'order_restock_admin',
                        'reference' => 'order:'.$orderId,
                        'note' => $note,
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::warning('Admin stock log failed: '.$e->getMessage());
            }
        }
    }
    public function index(Request $request){
        // Get filters for the view
        $filters = OrderHelper::getOrderFilters();
        
        // Build query with filters
        $query = ProductOrder::with(['orderDetails.product', 'user']);
        
        // Apply filters from request
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('delivery_status')) {
            $query->where('delivery_status', $request->delivery_status);
        }
        if ($request->filled('order_code')) {
            $query->where('order_code', 'like', '%' . $request->order_code . '%');
        }
        if ($request->filled('customer_name')) {
            $query->where('name', 'like', '%' . $request->customer_name . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $getAllOrder = $query->orderBy('created_at', 'desc')->paginate(20);
        $stats = OrderHelper::getOrderStats($request->all());
        
        return view('admin.product.order.index', compact('getAllOrder', 'stats', 'filters'));
    }

    //View Order By
    public function view($id){
        $order = ProductOrder::with(['user', 'orderDetails.product', 'statusHistory.changedBy'])
                            ->where('id', $id)
                            ->first();
        
        if(!$order){
            return redirect()->back()->with('error', 'Order not found.');
        }
        
        return view('admin.product.order.view', compact('order'));
    }

    //Order Delete
    public function destroy($id){
        $order = ProductOrder::find($id);
        
        if(!$order){
            return redirect()->back()->with('error', 'Order not found.');
        }
        
        // Check if order can be deleted
        if(!in_array($order->order_status, ['pending', 'cancelled'])){
            return redirect()->back()->with('error', 'Cannot delete order with status: ' . $order->status_label);
        }
        
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted successfully.');
    }

    //Update Order Status
    public function updateStatus(Request $request){
        $request->validate([
            'order_id' => 'required|exists:product_orders,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'notes' => 'nullable|string|max:1000',
            'tracking_number' => 'nullable|string|max:100',
            'shipping_carrier' => 'nullable|string|max:100',
        ]);
        
        try {
            $order = ProductOrder::with('orderDetails')->find($request->order_id);
            if (!$order) {
                return redirect()->back()->with('error', 'Order not found.');
            }

            // Guarded transitions
            if (!$order->canBeUpdatedTo($request->status)) {
                return redirect()->back()->with('error', 'Invalid transition from '.$order->order_status.' to '.$request->status);
            }
            
            // Update tracking info if provided
            if ($request->tracking_number || $request->shipping_carrier) {
                if ($request->tracking_number) {
                    $order->tracking_number = $request->tracking_number;
                }
                if ($request->shipping_carrier) {
                    $order->shipping_carrier = $request->shipping_carrier;
                }
                if ($request->status === 'shipped') {
                    $order->shipped_at = now();
                }
                if ($request->status === 'delivered') {
                    $order->delivered_at = now();
                }
                $order->save();
            }
            
            $result = DB::transaction(function () use ($order, $request) {
                $old = $order->order_status;
                $order->order_status = $request->status;
                if ($request->status === 'shipped') $order->shipped_at = now();
                if ($request->status === 'delivered') $order->delivered_at = now();
                if ($request->status === 'cancelled') $order->cancelled_at = now();
                $order->save();
                // History
                $order->statusHistory()->create([
                    'status' => $order->order_status,
                    'previous_status' => $old,
                    'notes' => $request->notes,
                    'changed_by' => Auth::id(),
                    'changed_at' => now(),
                ]);
                // If cancelled, restock
                if ($request->status === 'cancelled') {
                    foreach ($order->orderDetails as $item) {
                        $this->adjustStock((int)$item->product_id, (int)$item->qty, 'in', (int)$order->id, 'Admin cancel restock');
                    }
                }
                return true;
            });
            
            if($result){
                $order = ProductOrder::find($request->order_id);
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Order status updated successfully.',
                        'order' => [
                            'id' => $order->id,
                            'status' => $order->order_status,
                            'status_label' => $order->status_label,
                            'status_badge_class' => $order->status_badge_class,
                            'tracking_number' => $order->tracking_number,
                            'shipping_carrier' => $order->shipping_carrier,
                            'shipped_at' => $order->shipped_at,
                            'delivered_at' => $order->delivered_at,
                        ]
                    ]);
                }
                return redirect()->back()->with('success', 'Order status updated successfully.');
            } else {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to update order status.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Failed to update order status.');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Partial cancel a single line or quantity
    public function partialCancel(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:product_orders,id',
            'order_detail_id' => 'required|exists:product_order_details,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:500',
        ]);

        $order = ProductOrder::with('orderDetails')->find($request->order_id);
        if (!$order) return back()->with('error', 'Order not found');

        if (!in_array($order->order_status, ['pending','processing'])) {
            return back()->with('error', 'Partial cancel only allowed in Pending/Processing');
        }

        $line = ProductOrderDetails::where('id', $request->order_detail_id)->where('order_id', $order->id)->first();
        if (!$line) return back()->with('error', 'Order line not found');

        $cancelQty = min((int)$request->quantity, (int)$line->qty);

        DB::transaction(function () use ($order, $line, $cancelQty, $request) {
            // Restock: if payment already deducted or COD, add current_stock; also reduce reserved if any
            $inv = Inventory::where('product_id', $line->product_id)->lockForUpdate()->first();
            if ($inv) {
                // Release reservation if exists
                $inv->reserved_stock = max(0, (int)$inv->reserved_stock - $cancelQty);
                // Add back physical stock (safe for COD or paid)
                $inv->current_stock = (int)$inv->current_stock + $cancelQty;
                $inv->save();
            }
            // Reduce line qty and order totals
            $lineTotalPerUnit = ($line->qty > 0) ? ($line->price / $line->qty) : 0;
            $refundAmount = $lineTotalPerUnit * $cancelQty;
            $line->qty = (int)$line->qty - $cancelQty;
            if ($line->qty <= 0) {
                $line->delete();
            } else {
                $line->price = $lineTotalPerUnit * $line->qty;
                $line->save();
            }
            $order->total_amount = max(0, $order->total_amount - $refundAmount);
            $order->final_amount = max(0, ($order->final_amount ?: $order->total_amount) - $refundAmount);
            $order->save();
            // History
            $order->statusHistory()->create([
                'status' => $order->order_status,
                'previous_status' => $order->order_status,
                'notes' => 'Partial cancel: line '.$line->product_id.' qty '.$cancelQty.' reason: '.$request->reason,
                'changed_by' => Auth::id(),
                'changed_at' => now(),
            ]);
        });

        return back()->with('success', 'Line partially cancelled and stock updated.');
    }

    //Payment Status Change
    public function changePaymentStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_orders,id',
            'payment_status' => 'required|in:Pending,Processing,Paid,Unpaid,Refunded',
        ]);
        
        try {
            $order = ProductOrder::find($request->id);
            $order->payment_status = $request->payment_status;
            $order->save();
            
            // Log status change
            $order->statusHistory()->create([
                'status' => $order->order_status,
                'previous_status' => $order->order_status,
                'notes' => 'Payment status changed to: ' . $request->payment_status,
                'changed_by' => Auth::id(),
                'changed_at' => now(),
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment status updated successfully.',
                    'order' => [
                        'id' => $order->id,
                        'payment_status' => $order->payment_status,
                    ]
                ]);
            }
            
            return redirect()->back()->with('success', 'Payment status updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    //Delivery Status Change
    public function changeDeliveryStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_orders,id',
            'delivery_status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled',
        ]);
        
        $order = ProductOrder::find($request->id);
        $order->delivery_status = $request->delivery_status;
        $order->save();
        
        // Log status change
        $order->statusHistory()->create([
            'status' => $order->order_status,
            'previous_status' => $order->order_status,
            'notes' => 'Delivery status changed to: ' . $request->delivery_status,
            'changed_by' => Auth::id(),
            'changed_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Delivery status updated successfully.');
    }
    
    // Add admin notes
    public function addNotes(Request $request){
        $request->validate([
            'order_id' => 'required|exists:product_orders,id',
            'admin_notes' => 'required|string|max:1000',
        ]);
        
        try {
            $order = ProductOrder::find($request->order_id);
            $order->admin_notes = $request->admin_notes;
            $order->save();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Admin notes added successfully.',
                    'order' => [
                        'id' => $order->id,
                        'admin_notes' => $order->admin_notes,
                    ]
                ]);
            }
            
            return redirect()->back()->with('success', 'Admin notes added successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    // Send notification to customer
    public function sendNotification(Request $request){
        $request->validate([
            'order_id' => 'required|exists:product_orders,id',
            'type' => 'required|in:email,sms',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);
        
        try {
            $order = ProductOrder::with('user')->find($request->order_id);
            
            if (!$order) {
                return redirect()->back()->with('error', 'Order not found.');
            }
            
            // Create notification record
            $notification = new OrderNotification();
            $notification->order_id = $order->id;
            $notification->type = $request->type;
            $notification->subject = $request->subject;
            $notification->message = $request->message;
            $notification->sent_by = Auth::id();
            $notification->sent_at = now();
            $notification->status = 'sent';
            $notification->save();
            
            // Here you would typically integrate with email/SMS services
            // For now, we'll just log the notification
            \Log::info('Order notification sent', [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'type' => $request->type,
                'subject' => $request->subject,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification sent successfully.',
                    'order' => [
                        'id' => $order->id,
                    ]
                ]);
            }
            
            return redirect()->back()->with('success', 'Notification sent successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Failed to send order notification', [
                'order_id' => $request->order_id,
                'error' => $e->getMessage()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send notification: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to send notification: ' . $e->getMessage());
        }
    }
    
    // Get order statistics for dashboard
    public function getStats(Request $request){
        $filters = $request->all();
        $stats = OrderHelper::getOrderStats($filters);
        
        return response()->json($stats);
    }
}