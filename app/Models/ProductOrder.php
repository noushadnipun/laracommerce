<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;
    protected $table ="product_orders";
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
    ];

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    public function carts(){
        return $this->hasOne(App\Models\ProductCart::class);
    }

    public function orderDetails(){
        return $this->hasMany('App\Models\ProductOrderDetails', 'order_id', 'id');
    }
}
