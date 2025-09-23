<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\MenuHelper;

class MenuController extends Controller
{
    /**
     * Display a listing of menus.
     */
    public function index()
    {
        $menus = DB::table('admin_menus')->orderBy('name')->get();
        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create()
    {
        return view('admin.menu.create');
    }

    /**
     * Store a newly created menu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:admin_menus,name'
        ]);

        DB::table('admin_menus')->insert([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu created successfully.');
    }

    /**
     * Display the specified menu with its items.
     */
    public function show($id)
    {
        $menu = DB::table('admin_menus')->find($id);
        if (!$menu) {
            return redirect()->route('admin.menu.index')->with('error', 'Menu not found.');
        }

        $menuItems = DB::table('admin_menu_items')
            ->where('menu', $id)
            ->orderBy('sort')
            ->get();

        return view('admin.menu.show', compact('menu', 'menuItems'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit($id)
    {
        $menu = DB::table('admin_menus')->find($id);
        if (!$menu) {
            return redirect()->route('admin.menu.index')->with('error', 'Menu not found.');
        }

        return view('admin.menu.edit', compact('menu'));
    }

    /**
     * Update the specified menu.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:admin_menus,name,' . $id
        ]);

        DB::table('admin_menus')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'updated_at' => now()
            ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified menu.
     */
    public function destroy($id)
    {
        DB::table('admin_menu_items')->where('menu', $id)->delete();
        DB::table('admin_menus')->where('id', $id)->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu deleted successfully.');
    }

    /**
     * Show menu items management.
     */
    public function items($id)
    {
        $menu = DB::table('admin_menus')->find($id);
        if (!$menu) {
            return redirect()->route('admin.menu.index')->with('error', 'Menu not found.');
        }

        $menuItems = DB::table('admin_menu_items')
            ->where('menu', $id)
            ->orderBy('sort')
            ->get();

        return view('admin.menu.items', compact('menu', 'menuItems'));
    }

    /**
     * Store a new menu item.
     */
    public function storeItem(Request $request, $menuId)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'link' => 'required|string|max:500',
            'parent' => 'nullable|integer',
            'sort' => 'required|integer|min:0',
            'class' => 'nullable|string|max:255'
        ]);

        $parent = $request->parent ?: 0;
        $depth = $parent > 0 ? 1 : 0;

        DB::table('admin_menu_items')->insert([
            'menu' => $menuId,
            'label' => $request->label,
            'link' => $request->link,
            'parent' => $parent,
            'sort' => $request->sort,
            'class' => $request->class,
            'depth' => $depth,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.menu.items', $menuId)->with('success', 'Menu item created successfully.');
    }

    /**
     * Update menu item order.
     */
    public function updateOrder(Request $request, $menuId)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.sort' => 'required|integer'
        ]);

        foreach ($request->items as $item) {
            DB::table('admin_menu_items')
                ->where('id', $item['id'])
                ->where('menu', $menuId)
                ->update([
                    'sort' => $item['sort'],
                    'updated_at' => now()
                ]);
        }

        return response()->json(['success' => true, 'message' => 'Menu order updated successfully.']);
    }

    /**
     * Delete a menu item.
     */
    public function deleteItem($menuId, $itemId)
    {
        // Delete children first
        DB::table('admin_menu_items')->where('parent', $itemId)->delete();
        
        // Delete the item
        DB::table('admin_menu_items')
            ->where('id', $itemId)
            ->where('menu', $menuId)
            ->delete();

        return redirect()->route('admin.menu.items', $menuId)->with('success', 'Menu item deleted successfully.');
    }

    /**
     * Update a menu item.
     */
    public function updateItem(Request $request, $menuId, $itemId)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'link' => 'required|string|max:500',
            'parent' => 'nullable|integer',
            'sort' => 'required|integer|min:0',
            'class' => 'nullable|string|max:255'
        ]);

        $parent = $request->parent ?: 0;
        $depth = $parent > 0 ? 1 : 0;

        DB::table('admin_menu_items')
            ->where('id', $itemId)
            ->where('menu', $menuId)
            ->update([
                'label' => $request->label,
                'link' => $request->link,
                'parent' => $parent,
                'sort' => $request->sort,
                'class' => $request->class,
                'depth' => $depth,
                'updated_at' => now()
            ]);

        return redirect()->route('admin.menu.items', $menuId)->with('success', 'Menu item updated successfully.');
    }
}