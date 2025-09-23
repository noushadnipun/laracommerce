<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Helpers\MenuHelper;

class Menu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'menu';
    }

    /**
     * Get menu by name.
     */
    public static function getByName($menuName)
    {
        return MenuHelper::getByName($menuName);
    }

    /**
     * Get menu items as array.
     */
    public static function getMenuArray($menuName)
    {
        return MenuHelper::getMenuArray($menuName);
    }

    /**
     * Render menu HTML.
     */
    public static function render($location, $class = '', $ulClass = '')
    {
        return MenuHelper::renderMenu($location, $class, $ulClass);
    }
}

