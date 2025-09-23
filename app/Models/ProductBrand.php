<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'image'
    ];

     /**
     * Get catgeory Name by ID
     */

    public static function brandName($brand_id){
        if(!empty($brand_id)){
            $brand = ProductBrand::where('id', $brand_id)->first();
            return $brand ? $brand->name : 'Unknown Brand';
        }
        return 'Unknown Brand';
    }

    /**
     * Get brand slug by ID
     */
    public static function brandSlug($brand_id){
        if(!empty($brand_id)){
            $brand = ProductBrand::where('id', $brand_id)->first();
            return $brand ? $brand->slug : '#';
        }
        return '#';
    }

    /**
     * Get products for this brand
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
