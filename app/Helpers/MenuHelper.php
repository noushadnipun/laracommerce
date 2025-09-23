<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class MenuHelper
{
    /**
     * Get menu by name.
     */
    public static function getMenu($menuName)
    {
        return DB::table('admin_menus')->where('name', $menuName)->first();
    }

    /**
     * Get menu items as array.
     */
    public static function getMenuArray($menuName)
    {
        $menu = self::getMenu($menuName);
        if (!$menu) {
            return [];
        }

        $items = DB::table('admin_menu_items')
            ->where('menu', $menu->id)
            ->where('parent', 0)
            ->orderBy('sort')
            ->get();

        $menuArray = [];
        foreach ($items as $item) {
            $menuArray[] = self::buildMenuItemArray($item, $menu->id);
        }

        return $menuArray;
    }

    /**
     * Build menu item array recursively.
     */
    private static function buildMenuItemArray($item, $menuId)
    {
        $menuItem = [
            'id' => $item->id,
            'title' => $item->label,
            'url' => $item->link,
            'target' => '_self',
            'css_class' => $item->class,
            'icon' => '',
            'order' => $item->sort,
            'attributes' => [],
            'children' => []
        ];

        // Get children
        $children = DB::table('admin_menu_items')
            ->where('menu', $menuId)
            ->where('parent', $item->id)
            ->orderBy('sort')
            ->get();

        if ($children->count() > 0) {
            foreach ($children as $child) {
                $menuItem['children'][] = self::buildMenuItemArray($child, $menuId);
            }
        }

        return $menuItem;
    }

    /**
     * Render menu HTML.
     */
    public static function renderMenu($menuName, $class = '', $ulClass = '')
    {
        $menuItems = self::getMenuArray($menuName);
        
        if (empty($menuItems)) {
            return '';
        }

        $html = '<ul class="' . $ulClass . '">';
        
        foreach ($menuItems as $item) {
            $html .= self::renderMenuItem($item);
        }
        
        $html .= '</ul>';
        
        return $html;
    }

    /**
     * Render single menu item.
     */
    private static function renderMenuItem($item, $level = 0)
    {
        $hasChildren = !empty($item['children']);
        $cssClass = $item['css_class'] ? ' ' . $item['css_class'] : '';
        
        if ($hasChildren) {
            $cssClass .= ' has-children';
        }

        $html = '<li class="menu-item' . $cssClass . '">';
        
        // Menu link
        $target = $item['target'] ? ' target="' . $item['target'] . '"' : '';
        $icon = $item['icon'] ? '<i class="' . $item['icon'] . '"></i> ' : '';
        
        $html .= '<a href="' . $item['url'] . '"' . $target . '>';
        $html .= $icon . $item['title'];
        $html .= '</a>';
        
        // Children
        if ($hasChildren) {
            $subClass = $level === 0 ? 'sub-menu' : 'sub-sub-menu';
            $html .= '<ul class="' . $subClass . '">';
            
            foreach ($item['children'] as $child) {
                $html .= self::renderMenuItem($child, $level + 1);
            }
            
            $html .= '</ul>';
        }
        
        $html .= '</li>';
        
        return $html;
    }

    /**
     * Create default menus.
     */
    public static function createDefaultMenus()
    {
        $menus = [
            'primary',
            'secondary', 
            'footer-1',
            'footer-2',
            'mobile'
        ];

        foreach ($menus as $menuName) {
            $existing = DB::table('admin_menus')->where('name', $menuName)->first();
            if (!$existing) {
                DB::table('admin_menus')->insert([
                    'name' => $menuName,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Create default menu items.
     */
    public static function createDefaultMenuItems()
    {
        // Primary menu items
        $primaryMenu = DB::table('admin_menus')->where('name', 'primary')->first();
        if ($primaryMenu) {
            $primaryItems = [
                ['label' => 'Home', 'link' => '/', 'sort' => 1, 'class' => 'fa fa-home'],
                ['label' => 'Products', 'link' => '/products', 'sort' => 2, 'class' => 'fa fa-shopping-bag'],
                ['label' => 'About', 'link' => '/about', 'sort' => 3, 'class' => 'fa fa-info-circle'],
                ['label' => 'Contact', 'link' => '/contact', 'sort' => 4, 'class' => 'fa fa-envelope']
            ];

            foreach ($primaryItems as $itemData) {
                $existing = DB::table('admin_menu_items')
                    ->where('menu', $primaryMenu->id)
                    ->where('label', $itemData['label'])
                    ->first();
                if (!$existing) {
                    DB::table('admin_menu_items')->insert(array_merge($itemData, [
                        'menu' => $primaryMenu->id,
                        'parent' => 0,
                        'depth' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]));
                }
            }
        }

        // Secondary menu items
        $secondaryMenu = DB::table('admin_menus')->where('name', 'secondary')->first();
        if ($secondaryMenu) {
            $secondaryItems = [
                ['label' => 'My Account', 'link' => '/customer/profile', 'sort' => 1],
                ['label' => 'My Cart', 'link' => '/my/cart', 'sort' => 2],
                ['label' => 'Checkout', 'link' => '/checkout', 'sort' => 3],
                ['label' => 'Login', 'link' => '/customer/login', 'sort' => 4]
            ];

            foreach ($secondaryItems as $itemData) {
                $existing = DB::table('admin_menu_items')
                    ->where('menu', $secondaryMenu->id)
                    ->where('label', $itemData['label'])
                    ->first();
                if (!$existing) {
                    DB::table('admin_menu_items')->insert(array_merge($itemData, [
                        'menu' => $secondaryMenu->id,
                        'parent' => 0,
                        'depth' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]));
                }
            }
        }

        // Footer menu 1 items
        $footerMenu1 = DB::table('admin_menus')->where('name', 'footer-1')->first();
        if ($footerMenu1) {
            $footerItems1 = [
                ['label' => 'About Us', 'link' => '/about', 'sort' => 1],
                ['label' => 'Contact', 'link' => '/contact', 'sort' => 2],
                ['label' => 'Privacy Policy', 'link' => '/privacy-policy', 'sort' => 3],
                ['label' => 'Terms of Service', 'link' => '/terms-of-service', 'sort' => 4]
            ];

            foreach ($footerItems1 as $itemData) {
                $existing = DB::table('admin_menu_items')
                    ->where('menu', $footerMenu1->id)
                    ->where('label', $itemData['label'])
                    ->first();
                if (!$existing) {
                    DB::table('admin_menu_items')->insert(array_merge($itemData, [
                        'menu' => $footerMenu1->id,
                        'parent' => 0,
                        'depth' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]));
                }
            }
        }

        // Footer menu 2 items
        $footerMenu2 = DB::table('admin_menus')->where('name', 'footer-2')->first();
        if ($footerMenu2) {
            $footerItems2 = [
                ['label' => 'My Account', 'link' => '/customer/profile', 'sort' => 1],
                ['label' => 'Order History', 'link' => '/customer/orders', 'sort' => 2],
                ['label' => 'Wishlist', 'link' => '/my/wishlist', 'sort' => 3],
                ['label' => 'Compare', 'link' => '/my/compare', 'sort' => 4]
            ];

            foreach ($footerItems2 as $itemData) {
                $existing = DB::table('admin_menu_items')
                    ->where('menu', $footerMenu2->id)
                    ->where('label', $itemData['label'])
                    ->first();
                if (!$existing) {
                    DB::table('admin_menu_items')->insert(array_merge($itemData, [
                        'menu' => $footerMenu2->id,
                        'parent' => 0,
                        'depth' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]));
                }
            }
        }
    }

    /**
     * Get menu items for frontend (compatible with existing Menu::getByName)
     */
    public static function getByName($menuName)
    {
        $items = self::getMenuArray($menuName);
        return self::transformToLegacy($items);
    }

    /**
     * Transform menu array to legacy structure expected by views
     * Keys: label, link, child
     */
    private static function transformToLegacy(array $items)
    {
        $result = [];
        foreach ($items as $item) {
            $children = isset($item['children']) && is_array($item['children'])
                ? self::transformToLegacy($item['children'])
                : [];

            $result[] = [
                'id' => $item['id'] ?? null,
                'label' => $item['title'] ?? ($item['label'] ?? ''),
                'link' => $item['url'] ?? ($item['link'] ?? '#'),
                'child' => $children,
                'class' => $item['css_class'] ?? ($item['class'] ?? ''),
            ];
        }
        return $result;
    }
}

