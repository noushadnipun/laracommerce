<?php

namespace App\Models\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSizeGuide extends Model
{
    use HasFactory;

    protected $table = 'product_size_guides';

    protected $fillable = [
        'product_id',
        'size_type',
        'title',
        'description',
        'size_chart',
        'measurement_guide',
        'is_active'
    ];

    protected $casts = [
        'size_chart' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the product that owns the size guide
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get size guide for a specific product and type
     */
    public static function getSizeGuide($productId, $sizeType = 'clothing')
    {
        return self::where('product_id', $productId)
                   ->where('size_type', $sizeType)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Get all size guides for a product
     */
    public static function getProductSizeGuides($productId)
    {
        return self::where('product_id', $productId)
                   ->where('is_active', true)
                   ->get();
    }

    /**
     * Create default size chart for clothing
     */
    public static function createDefaultClothingChart()
    {
        return [
            'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            'measurements' => [
                'chest' => ['32-34', '34-36', '36-38', '38-40', '40-42', '42-44'],
                'waist' => ['28-30', '30-32', '32-34', '34-36', '36-38', '38-40'],
                'length' => ['26', '27', '28', '29', '30', '31']
            ],
            'units' => 'inches'
        ];
    }

    /**
     * Create default size chart for shoes
     */
    public static function createDefaultShoeChart()
    {
        return [
            'sizes' => ['6', '7', '8', '9', '10', '11', '12'],
            'measurements' => [
                'length' => ['9.5', '10', '10.5', '11', '11.5', '12', '12.5'],
                'width' => ['3.5', '3.7', '3.9', '4.1', '4.3', '4.5', '4.7']
            ],
            'units' => 'inches'
        ];
    }
}
