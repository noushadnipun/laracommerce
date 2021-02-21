<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'brand_id',
        'title',
        'description',
        'slug',
        'code',
        'regular_price',
        'sale_price',
        'purchase_price',
        'attribute',
        'refundable',
        'shipping_type',
        'shipping_cost',
        'total_stock',
        'current_stock',
        'product_image',
        'featured_image',
        'visibility'
    ];
    //store with array
     protected $casts = [
        'product_image' => 'array',
        'attribute' => 'array',
    ];

    /**
     * Get Product by category ID
     */
    public static function productByCatId($category_id){
        if(!empty($category_id)){
            return Product::orderBy('created_at', 'DESC')->where('visibility', '1')->whereRaw("FIND_IN_SET($category_id, category_id)");
        }
    }


    /**
     * Get Product by Category ID where has Comma
     */
    public static function productByCatIdHasComma($category_id){
        return Product::orderBy('created_at', 'DESC')->where('visibility', '1')->whereRaw('category_id', $category_id);
    }
}
