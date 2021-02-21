<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $table ="product_categories";
    protected $fillable = [
        'name',
        'description',
        'slug',
        'image',
        'parent_id',
        'visibility'
    ];

    /**
     * Get catgeory Name by ID
     */

    public static function categoryName($category_id){
        if(!empty($category_id)){
            $category = ProductCategory::where('id', $category_id)->first();
            return $category->name;
        }
    }

     /**
     * Get catgeory Slug by ID
     */

    public static function categorySlug($category_id){
        $category = ProductCategory::where('id', $category_id)->first();
        return $category->slug;
    }

}
