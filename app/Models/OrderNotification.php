<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderNotification extends Model
{
    use HasFactory;
    
    protected $table = 'order_notifications';
    
    protected $fillable = [
        'order_id',
        'type',
        'status',
        'recipient',
        'subject',
        'message',
        'metadata',
        'sent_at',
        'delivered_at',
        'error_message',
        'sent_by',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function order(){
        return $this->belongsTo(ProductOrder::class, 'order_id');
    }
    
    public function sentBy(){
        return $this->belongsTo(User::class, 'sent_by');
    }
    
    public function getStatusBadgeClassAttribute(){
        $statusClasses = [
            'pending' => 'badge-warning',
            'sent' => 'badge-info',
            'failed' => 'badge-danger',
            'delivered' => 'badge-success',
        ];
        
        return $statusClasses[$this->status] ?? 'badge-secondary';
    }
    
    public function getTypeIconAttribute(){
        $icons = [
            'email' => 'fas fa-envelope',
            'sms' => 'fas fa-sms',
            'push' => 'fas fa-bell',
        ];
        
        return $icons[$this->type] ?? 'fas fa-bell';
    }
}