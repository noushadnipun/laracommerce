<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
    use HasFactory;
    protected $table ="product_carts";
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'ip_address',
        'qty'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'id', 'user_id');
    }

    public function order(){
        return $this->belongsTo('App\Models\ProductOrder', 'id', 'order_id');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product', 'id', 'product_id');
    }
}
