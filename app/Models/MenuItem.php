<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'target',
        'css_class',
        'icon',
        'order',
        'is_active',
        'attributes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'attributes' => 'array'
    ];

    /**
     * Get the menu that owns the menu item.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the parent menu item.
     */
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get the child menu items.
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get all descendants.
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Check if menu item has children.
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get the full URL with domain if needed.
     */
    public function getFullUrlAttribute()
    {
        if (filter_var($this->url, FILTER_VALIDATE_URL)) {
            return $this->url;
        }

        if (str_starts_with($this->url, '/')) {
            return url($this->url);
        }

        return url('/' . $this->url);
    }

    /**
     * Scope for active menu items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for root menu items.
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}