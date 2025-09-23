<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;
    
    protected $table = 'product_attribute_values';
    
    protected $fillable = [
        'attribute_id',
        'value',
        'color_code',
        'image',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get the attribute that owns this value
     */
    public function attribute(){
        return $this->belongsTo('App\Models\ProductAttribute', 'attribute_id', 'id');
    }

    /**
     * Scope for active values
     */
    public function scopeActive($query){
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered values
     */
    public function scopeOrdered($query){
        return $query->orderBy('sort_order');
    }
}
