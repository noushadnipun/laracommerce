<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    
    protected $table = 'product_attributes';
    
    protected $fillable = [
        'name',
        'type',
        'display_type',
        'is_required',
        'description',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function values(){
        return $this->hasMany('App\Models\ProductAttributeValue', 'attribute_id', 'id');
    }

    /**
     * Get active values ordered by sort_order
     */
    public function activeValues(){
        return $this->hasMany('App\Models\ProductAttributeValue', 'attribute_id', 'id')
                    ->where('is_active', true)
                    ->orderBy('sort_order');
    }

    /**
     * Get attribute types
     */
    public static function getTypes(){
        return [
            'select' => 'Select Dropdown',
            'color' => 'Color Swatch',
            'text' => 'Text Input',
            'image' => 'Image Select',
            'radio' => 'Radio Button',
            'checkbox' => 'Checkbox'
        ];
    }

    /**
     * Get display types
     */
    public static function getDisplayTypes(){
        return [
            'dropdown' => 'Dropdown',
            'radio' => 'Radio Buttons',
            'checkbox' => 'Checkboxes',
            'color_swatch' => 'Color Swatches',
            'image_grid' => 'Image Grid',
            'text_input' => 'Text Input'
        ];
    }
}
