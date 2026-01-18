<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductOrder extends Model
{
    use HasFactory;
    
    protected $table = "product_orders";
    
    protected $fillable = [
        'order_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_thana',
        'customer_postal_code',
        'customer_city',
        'customer_country',
        'total_amount',
        'shipping_cost',
        'use_coupone',
        'coupone_discount',
        'currency',
        'note',
        'payment_status',
        'payment_type',
        'delivery_status',
        'shiping_type',
        // New fields
        'order_status',
        'order_notes',
        'admin_notes',
        'tracking_number',
        'shipping_carrier',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'email_sent',
        'sms_sent',
        'last_notification_sent',
        'tax_amount',
        'discount_amount',
        'final_amount',
        'tran_id', // Add tran_id to fillable
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'last_notification_sent' => 'datetime',
        'email_sent' => 'boolean',
        'sms_sent' => 'boolean',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'coupone_discount' => 'decimal:2',
    ];

    // Accessor for coupone_discount to handle null/empty values
    public function getCouponeDiscountAttribute($value)
    {
        return $value ? (float) $value : 0;
    }

    // Relationships
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    
    public function carts(){
        return $this->hasOne(App\Models\ProductCart::class);
    }

    public function orderDetails(){
        return $this->hasMany('App\Models\ProductOrderDetails', 'order_id', 'id');
    }
    
    public function statusHistory(){
        return $this->hasMany('App\Models\OrderStatusHistory', 'order_id');
    }
    
    public function notifications(){
        return $this->hasMany('App\Models\OrderNotification', 'order_id');
    }

    // Scopes
    public function scopeByStatus($query, $status){
        return $query->where('order_status', $status);
    }
    
    public function scopeByPaymentStatus($query, $status){
        return $query->where('payment_status', $status);
    }
    
    public function scopeRecent($query, $days = 30){
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    // Accessors
    public function getStatusBadgeClassAttribute(){
        $statusClasses = [
            'pending' => 'badge-warning',
            'confirmed' => 'badge-info',
            'processing' => 'badge-primary',
            'shipped' => 'badge-secondary',
            'delivered' => 'badge-success',
            'cancelled' => 'badge-danger',
            'returned' => 'badge-dark',
        ];
        
        return $statusClasses[$this->order_status] ?? 'badge-secondary';
    }
    
    public function getStatusLabelAttribute(){
        $labels = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'returned' => 'Returned',
        ];
        
        return $labels[$this->order_status] ?? 'Unknown';
    }
    
    public function getIsCancellableAttribute(){
        return in_array($this->order_status, ['pending', 'confirmed']);
    }
    
    public function getIsShippableAttribute(){
        return in_array($this->order_status, ['confirmed', 'processing']);
    }
    
    public function getIsDeliverableAttribute(){
        return $this->order_status === 'shipped';
    }

    // Methods
    public function updateStatus($newStatus, $notes = null, $adminId = null){
        $oldStatus = $this->order_status;
        
        $this->order_status = $newStatus;
        
        // Update timestamps based on status
        switch($newStatus){
            case 'shipped':
                $this->shipped_at = now();
                break;
            case 'delivered':
                $this->delivered_at = now();
                break;
            case 'cancelled':
                $this->cancelled_at = now();
                break;
        }
        
        $this->save();
        
        // Log status change
        $this->statusHistory()->create([
            'status' => $newStatus,
            'previous_status' => $oldStatus,
            'notes' => $notes,
            'changed_by' => $adminId,
            'changed_at' => now(),
        ]);
        
        return $this;
    }
    
    public function canBeUpdatedTo($newStatus){
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => ['returned'],
            'cancelled' => [],
            'returned' => ['processing'],
        ];
        
        return in_array($newStatus, $allowedTransitions[$this->order_status] ?? []);
    }
    
    public function getTotalItemsAttribute(){
        return $this->orderDetails->sum('qty');
    }
    
    public function getFormattedTotalAttribute(){
        return '৳' . number_format($this->total_amount, 2);
    }
    
    public function getFormattedFinalAmountAttribute(){
        return '৳' . number_format($this->final_amount ?: $this->total_amount, 2);
    }
}
