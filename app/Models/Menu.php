<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the menu items for the menu.
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class)->where('parent_id', null)->orderBy('order');
    }

    /**
     * Get all menu items including children.
     */
    public function allItems()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    /**
     * Get menu by location.
     */
    public static function getByLocation($location)
    {
        return static::where('location', $location)
                    ->where('is_active', true)
                    ->with(['items.children'])
                    ->first();
    }

    /**
     * Get menu items as array for frontend.
     */
    public function getMenuArray()
    {
        $items = $this->items;
        $menuArray = [];

        foreach ($items as $item) {
            $menuArray[] = $this->buildMenuItemArray($item);
        }

        return $menuArray;
    }

    /**
     * Build menu item array recursively.
     */
    private function buildMenuItemArray($item)
    {
        $menuItem = [
            'id' => $item->id,
            'title' => $item->title,
            'url' => $item->url,
            'target' => $item->target,
            'css_class' => $item->css_class,
            'icon' => $item->icon,
            'order' => $item->order,
            'attributes' => $item->attributes,
            'children' => []
        ];

        if ($item->children->count() > 0) {
            foreach ($item->children as $child) {
                $menuItem['children'][] = $this->buildMenuItemArray($child);
            }
        }

        return $menuItem;
    }
}