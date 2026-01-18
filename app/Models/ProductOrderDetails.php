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
        'product_title',
        'product_code',
        'featured_image',
        'unit_price',
        'currency',
        'line_total',
        'brand_name',
        'category_name',
    ];

    //store with array
     protected $casts = [
        'attribute' => 'array',
    ];

    public function products(){
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    // Accessors to prioritize snapshot data over live product data
    public function getDisplayTitleAttribute(){
        return $this->product_title ?? ($this->product->title ?? 'Product Not Found');
    }
    
    public function getDisplayCodeAttribute(){
        return $this->product_code ?? ($this->product->code ?? 'N/A');
    }
    
    public function getDisplayImageAttribute(){
        return $this->featured_image ?? ($this->product && method_exists($this->product, 'getFeaturedImageUrl') ? $this->product->getFeaturedImageUrl() : null);
    }
    
    public function getDisplayUnitPriceAttribute(){
        return $this->unit_price ?? ($this->qty ? $this->price / $this->qty : 0);
    }
    
    public function getDisplayLineTotalAttribute(){
        return $this->line_total ?? $this->price;
    }
    
    public function getDisplayBrandAttribute(){
        return $this->brand_name ?? ($this->product && $this->product->brand ? $this->product->brand->name : 'N/A');
    }
    
    public function getDisplayCategoryAttribute(){
        return $this->category_name ?? ($this->product && $this->product->category ? $this->product->category->name : 'N/A');
    }
}
