<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;
    
    protected $table = 'order_status_history';
    
    // Disable timestamps since this table uses 'changed_at' instead
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'status',
        'previous_status',
        'notes',
        'changed_by',
        'changed_at',
        'metadata',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function order(){
        return $this->belongsTo(ProductOrder::class, 'order_id');
    }
    
    public function changedBy(){
        return $this->belongsTo(User::class, 'changed_by');
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
        
        return $labels[$this->status] ?? 'Unknown';
    }
    
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
        
        return $statusClasses[$this->status] ?? 'badge-secondary';
    }
}