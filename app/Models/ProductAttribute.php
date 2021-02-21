<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $table = 'product_attributes';
    protected $fillable = [
        'name'
    ];

    public function values(){
        return $this->hasMany('App\Models\ProductAttributeValue', 'attributes_id', 'id');
    }
}
