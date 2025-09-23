<?php

namespace App\Helpers\Order;

use App\Models\ProductOrder;
use App\Models\OrderStatusHistory;
use App\Models\OrderNotification;
use App\Models\Inventory;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderHelper
{
    /**
     * Get order filters for admin panel
     */
    public static function getOrderFilters()
    {
        return [
            'statuses' => [
                'pending' => 'Pending',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
                'refunded' => 'Refunded'
            ],
            'payment_statuses' => [
                'pending' => 'Pending',
                'paid' => 'Paid',
                'failed' => 'Failed',
                'refunded' => 'Refunded'
            ],
            'delivery_statuses' => [
                'pending' => 'Pending',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled'
            ]
        ];
    }

    /**
     * Get order statistics for admin dashboard
     */
    public static function getOrderStats($filters = [])
    {
        $query = ProductOrder::query();

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('order_status', $filters['status']);
        }
        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }
        if (!empty($filters['delivery_status'])) {
            $query->where('delivery_status', $filters['delivery_status']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $totalOrders = $query->count();
        $totalRevenue = $query->sum('final_amount');
        
        $stats = [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'pending_orders' => $query->where('order_status', 'pending')->count(),
            'processing_orders' => $query->where('order_status', 'processing')->count(),
            'shipped_orders' => $query->where('order_status', 'shipped')->count(),
            'delivered_orders' => $query->where('order_status', 'delivered')->count(),
            'cancelled_orders' => $query->where('order_status', 'cancelled')->count(),
            'paid_orders' => $query->where('payment_status', 'paid')->count(),
            'pending_payment' => $query->where('payment_status', 'pending')->count(),
        ];

        return $stats;
    }

    /**
     * Update order status with inventory management
     */
    public static function updateOrderStatus($orderId, $newStatus, $notes = null, $userId = null)
    {
        try {
            DB::beginTransaction();

            $order = ProductOrder::with('orderDetails.product')->findOrFail($orderId);
            $oldStatus = $order->order_status;

            // Validate status transition
            if (!self::canUpdateStatus($oldStatus, $newStatus)) {
                throw new \Exception("Cannot update status from {$oldStatus} to {$newStatus}");
            }

            // Update order status
            $order->order_status = $newStatus;
            $order->save();

            // Log status change
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $newStatus,
                'previous_status' => $oldStatus,
                'notes' => $notes,
                'changed_by' => $userId,
                'changed_at' => now()
            ]);

            // Handle inventory based on status
            self::handleInventoryForStatus($order, $newStatus, $oldStatus);

            // Send notifications
            self::sendOrderNotification($order, $newStatus, $notes);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order status update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if status can be updated
     */
    public static function canUpdateStatus($currentStatus, $newStatus)
    {
        $allowedTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'cancelled'],
            'delivered' => ['refunded'],
            'cancelled' => ['processing'], // Allow reactivation
            'refunded' => []
        ];

        return in_array($newStatus, $allowedTransitions[$currentStatus] ?? []);
    }

    /**
     * Handle inventory based on order status
     */
    public static function handleInventoryForStatus($order, $newStatus, $oldStatus)
    {
        foreach ($order->orderDetails as $detail) {
            $product = $detail->product;
            $quantity = $detail->qty;

            switch ($newStatus) {
                case 'processing':
                    if ($oldStatus === 'pending') {
                        self::reserveInventory($product, $quantity, $order->id);
                    }
                    break;

                case 'shipped':
                    if ($oldStatus === 'processing') {
                        self::deductInventory($product, $quantity, $order->id);
                    }
                    break;

                case 'cancelled':
                    if ($oldStatus === 'processing') {
                        self::releaseInventory($product, $quantity, $order->id);
                    } elseif ($oldStatus === 'shipped') {
                        // Already deducted, no action needed
                    }
                    break;

                case 'refunded':
                    if ($oldStatus === 'delivered') {
                        self::addInventory($product, $quantity, $order->id, 'refund');
                    }
                    break;
            }
        }
    }

    /**
     * Reserve inventory for order
     */
    public static function reserveInventory($product, $quantity, $orderId)
    {
        $inventory = Inventory::where('product_id', $product->id)->first();
        
        if ($inventory && $inventory->current_stock >= $quantity) {
            $inventory->current_stock -= $quantity;
            $inventory->reserved_stock += $quantity;
            $inventory->save();

            // Log stock movement
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'reserved',
                'quantity' => $quantity,
                'previous_stock' => $inventory->current_stock + $quantity,
                'new_stock' => $inventory->current_stock,
                'reference_type' => 'order',
                'reference_id' => $orderId,
                'notes' => "Reserved for order #{$orderId}",
                'user_id' => auth()->id()
            ]);
        }
    }

    /**
     * Deduct inventory (ship order)
     */
    public static function deductInventory($product, $quantity, $orderId)
    {
        $inventory = Inventory::where('product_id', $product->id)->first();
        
        if ($inventory && $inventory->reserved_stock >= $quantity) {
            $inventory->reserved_stock -= $quantity;
            $inventory->save();

            // Log stock movement
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'sold',
                'quantity' => $quantity,
                'previous_stock' => $inventory->reserved_stock + $quantity,
                'new_stock' => $inventory->reserved_stock,
                'reference_type' => 'order',
                'reference_id' => $orderId,
                'notes' => "Shipped for order #{$orderId}",
                'user_id' => auth()->id()
            ]);
        }
    }

    /**
     * Release reserved inventory (cancel order)
     */
    public static function releaseInventory($product, $quantity, $orderId)
    {
        $inventory = Inventory::where('product_id', $product->id)->first();
        
        if ($inventory && $inventory->reserved_stock >= $quantity) {
            $inventory->current_stock += $quantity;
            $inventory->reserved_stock -= $quantity;
            $inventory->save();

            // Log stock movement
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'released',
                'quantity' => $quantity,
                'previous_stock' => $inventory->current_stock - $quantity,
                'new_stock' => $inventory->current_stock,
                'reference_type' => 'order',
                'reference_id' => $orderId,
                'notes' => "Released from cancelled order #{$orderId}",
                'user_id' => auth()->id()
            ]);
        }
    }

    /**
     * Add inventory back (refund)
     */
    public static function addInventory($product, $quantity, $orderId, $type = 'refund')
    {
        $inventory = Inventory::where('product_id', $product->id)->first();
        
        if ($inventory) {
            $inventory->current_stock += $quantity;
            $inventory->save();

            // Log stock movement
            StockMovement::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'previous_stock' => $inventory->current_stock - $quantity,
                'new_stock' => $inventory->current_stock,
                'reference_type' => 'order',
                'reference_id' => $orderId,
                'notes' => "Added back from {$type} order #{$orderId}",
                'user_id' => auth()->id()
            ]);
        }
    }

    /**
     * Send order notification
     */
    public static function sendOrderNotification($order, $status, $notes = null)
    {
        try {
            $notification = OrderNotification::create([
                'order_id' => $order->id,
                'type' => 'email',
                'status' => 'pending',
                'recipient' => $order->user->email ?? $order->customer_phone,
                'subject' => "Order #{$order->order_code} Status Update",
                'message' => self::generateNotificationMessage($order, $status, $notes),
                'metadata' => [
                    'status' => $status,
                    'notes' => $notes,
                    'order_code' => $order->order_code
                ],
                'sent_by' => auth()->id()
            ]);

            // Here you would integrate with your email service
            // For now, just mark as sent
            $notification->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

        } catch (\Exception $e) {
            Log::error('Order notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate notification message
     */
    public static function generateNotificationMessage($order, $status, $notes = null)
    {
        $statusMessages = [
            'pending' => 'Your order has been received and is being processed.',
            'processing' => 'Your order is being prepared for shipment.',
            'shipped' => 'Your order has been shipped and is on its way.',
            'delivered' => 'Your order has been delivered successfully.',
            'cancelled' => 'Your order has been cancelled.',
            'refunded' => 'Your order has been refunded.'
        ];

        $message = "Dear {$order->customer_name},\n\n";
        $message .= "Your order #{$order->order_code} status has been updated to: " . ucfirst($status) . "\n\n";
        $message .= $statusMessages[$status] ?? "Your order status has been updated.";
        
        if ($notes) {
            $message .= "\n\nAdditional Notes: {$notes}";
        }

        $message .= "\n\nThank you for your business!";

        return $message;
    }

    /**
     * Get order summary for customer
     */
    public static function getOrderSummary($orderId)
    {
        $order = ProductOrder::with(['orderDetails.product', 'statusHistory.changedBy'])
            ->findOrFail($orderId);

        return [
            'order' => $order,
            'items' => $order->orderDetails,
            'status_history' => $order->statusHistory,
            'can_cancel' => $order->isCancellable,
            'can_track' => in_array($order->order_status, ['shipped', 'delivered']),
            'tracking_info' => [
                'tracking_number' => $order->tracking_number,
                'shipping_carrier' => $order->shipping_carrier,
                'shipped_at' => $order->shipped_at,
                'delivered_at' => $order->delivered_at
            ]
        ];
    }

    /**
     * Get customer order history
     */
    public static function getCustomerOrderHistory($customerId, $limit = 10)
    {
        return ProductOrder::where('user_id', $customerId)
            ->with(['orderDetails.product'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Calculate order totals
     */
    public static function calculateOrderTotals($order)
    {
        $subtotal = $order->orderDetails->sum(function($detail) {
            return $detail->qty * $detail->price;
        });

        $tax = $order->tax_amount ?? 0;
        $discount = $order->discount_amount ?? 0;
        $shipping = $order->shipping_cost ?? 0;

        $total = $subtotal + $tax + $shipping - $discount;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'shipping' => $shipping,
            'total' => $total
        ];
    }

    /**
     * Get status badge class
     */
    public static function getStatusBadgeClass($status)
    {
        $statusClasses = [
            'pending' => 'warning',
            'processing' => 'primary',
            'shipped' => 'secondary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'dark',
        ];
        
        return $statusClasses[$status] ?? 'secondary';
    }

    /**
     * Get payment badge class
     */
    public static function getPaymentBadgeClass($status)
    {
        $statusClasses = [
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'refunded' => 'dark',
        ];
        
        return $statusClasses[$status] ?? 'secondary';
    }

    /**
     * Get order status options
     */
    public static function getOrderStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded'
        ];
    }

    /**
     * Get payment status options
     */
    public static function getPaymentStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded'
        ];
    }
}
