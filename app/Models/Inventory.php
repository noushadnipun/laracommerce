<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 'inventory';
    protected $fillable = [
        'product_id',
        'current_stock',
        'total_stock',
        'reserved_stock',
        'low_stock_threshold',
        'unit_cost',
        'total_value',
        'is_active'
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'total_value' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Get the product that owns the inventory.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the stock movements for the inventory.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'product_id', 'product_id');
    }

    /**
     * Check if stock is low.
     */
    public function isLowStock()
    {
        return $this->current_stock <= $this->low_stock_threshold;
    }

    /**
     * Check if out of stock.
     */
    public function isOutOfStock()
    {
        return $this->current_stock <= 0;
    }

    /**
     * Get available stock (current - reserved).
     */
    public function getAvailableStock()
    {
        return max(0, $this->current_stock - $this->reserved_stock);
    }

    /**
     * Update stock levels.
     */
    public function updateStock($type, $quantity, $notes = '', $referenceType = null, $referenceId = null)
    {
        $previousStock = $this->current_stock;
        
        switch ($type) {
            case 'in':
                $this->current_stock += $quantity;
                $this->total_stock += $quantity;
                break;
            case 'out':
                $this->current_stock = max(0, $this->current_stock - $quantity);
                break;
            case 'adjustment':
                $this->current_stock = max(0, $quantity);
                break;
            case 'reserve':
                $this->reserved_stock += $quantity;
                break;
            case 'unreserve':
                $this->reserved_stock = max(0, $this->reserved_stock - $quantity);
                break;
        }

        $this->total_value = $this->unit_cost * $this->current_stock;
        $this->save();

        // Log stock movement
        StockMovement::create([
            'product_id' => $this->product_id,
            'type' => $type,
            'quantity' => $quantity,
            'previous_stock' => $previousStock,
            'new_stock' => $this->current_stock,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'user_id' => auth()->id() ?? 1
        ]);

        return $this;
    }

    /**
     * Scope for low stock products.
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= low_stock_threshold');
    }

    /**
     * Scope for out of stock products.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    /**
     * Scope for in stock products.
     */
    public function scopeInStock($query)
    {
        return $query->where('current_stock', '>', 0);
    }
}