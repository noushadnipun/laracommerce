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
            return $brand->name;
        }
    }
}
