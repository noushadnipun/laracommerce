<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view permissions')->only(['index', 'show']);
        $this->middleware('permission:create permissions')->only(['create', 'store']);
        $this->middleware('permission:edit permissions')->only(['edit', 'update']);
        $this->middleware('permission:delete permissions')->only(['destroy']);
    }

    /**
     * Display a listing of permissions
     */
    public function index(Request $request)
    {
        $query = Permission::withCount('roles');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by module
        if ($request->has('module') && $request->module) {
            $query->where('name', 'like', $request->module . '%');
        }

        $permissions = $query->paginate(20);
        
        // Get unique modules for filter
        $modules = Permission::selectRaw('SUBSTRING_INDEX(name, " ", 1) as module')
            ->distinct()
            ->pluck('module')
            ->sort()
            ->values();

        return view('admin.permission-management.index', compact('permissions', 'modules'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        return view('admin.permission-management.create');
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string|max:500',
            'module' => 'required|string|max:100',
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin_permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified permission
     */
    public function show(Permission $permission)
    {
        $permission->load(['roles', 'users']);
        return view('admin.permission-management.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission
     */
    public function edit(Permission $permission)
    {
        return view('admin.permission-management.edit', compact('permission'));
    }

    /**
     * Update the specified permission
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string|max:500',
        ]);

        $permission->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin_permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin_permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Bulk create permissions for a module
     */
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'module' => 'required|string|max:100',
            'actions' => 'required|array',
            'actions.*' => 'required|string|max:100',
        ]);

        $created = [];
        foreach ($request->actions as $action) {
            $permissionName = $request->module . ' ' . $action;
            
            if (!Permission::where('name', $permissionName)->exists()) {
                Permission::create([
                    'name' => $permissionName,
                    'description' => ucfirst($action) . ' ' . $request->module,
                ]);
                $created[] = $permissionName;
            }
        }

        $message = count($created) > 0 
            ? 'Created ' . count($created) . ' permissions: ' . implode(', ', $created)
            : 'No new permissions created (all already exist)';

        return redirect()->route('admin_permissions.index')
            ->with('success', $message);
    }
}
