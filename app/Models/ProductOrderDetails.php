<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
class ProductOrderDetails extends Model
{
    use HasFactory;
    protected $table = "product_order_details";
    protected $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'attribute',
        'qty',
        'price',
    ];

    //store with array
     protected $casts = [
        'attribute' => 'array',
    ];

    public function products(){
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
}
