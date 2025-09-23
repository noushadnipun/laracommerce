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
            return $category?->name;
        }
    }

     /**
     * Get catgeory Slug by ID
     */

    public static function categorySlug($category_id){
        $category = ProductCategory::where('id', $category_id)->first();
        return $category?->slug;
    }

    /**
     * Get products for this category
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Get subcategories for this category
     */
    public function subcategories()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    /**
     * Get parent category
     */
    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

}
