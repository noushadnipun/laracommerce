<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permission System Example</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .admin-only { background-color: #ffebee; }
        .editor-only { background-color: #e8f5e8; }
        .customer-only { background-color: #e3f2fd; }
        .permission-demo { background-color: #fff3e0; }
    </style>
</head>
<body>
    <h1>Laravel Permission System Example</h1>
    
    @auth
        <p>Welcome, {{ auth()->user()->name }}!</p>
        <p>Your Role: {{ App\Helpers\PermissionHelper::getUserRoleName() }}</p>
        <p>Your Permissions: {{ implode(', ', App\Helpers\PermissionHelper::getUserPermissions()) }}</p>
    @endauth

    <div class="section admin-only">
        <h2>Admin Only Section</h2>
        @role('admin')
            <p>✅ This content is only visible to admins!</p>
            <p>You can manage users, settings, and everything.</p>
        @else
            <p>❌ You need admin role to see this content.</p>
        @endrole
    </div>

    <div class="section editor-only">
        <h2>Editor Only Section</h2>
        @role('editor')
            <p>✅ This content is visible to editors!</p>
            <p>You can manage products, categories, and content.</p>
        @else
            <p>❌ You need editor role to see this content.</p>
        @endrole
    </div>

    <div class="section customer-only">
        <h2>Customer Only Section</h2>
        @role('customer')
            <p>✅ This content is visible to customers!</p>
            <p>You can view products and place orders.</p>
        @else
            <p>❌ You need customer role to see this content.</p>
        @endrole
    </div>

    <div class="section permission-demo">
        <h2>Permission-Based Content</h2>
        
        @permission('view-dashboard')
            <p>✅ You can view the dashboard!</p>
        @endpermission

        @permission('create-products')
            <p>✅ You can create products!</p>
        @endpermission

        @permission('delete-products')
            <p>✅ You can delete products!</p>
        @endpermission

        @permission('manage-cart')
            <p>✅ You can manage your cart!</p>
        @endpermission

        @anypermission(['view-settings', 'edit-settings'])
            <p>✅ You have settings access!</p>
        @endanypermission
    </div>

    <div class="section">
        <h2>Controller Usage Examples</h2>
        <pre>
// In Controller Constructor:
public function __construct()
{
    $this->middleware('permission:view-products', ['only' => ['index']]);
    $this->middleware('permission:create-products', ['only' => ['create', 'store']]);
    $this->middleware('permission:edit-products', ['only' => ['edit', 'update']]);
    $this->middleware('permission:delete-products', ['only' => ['destroy']]);
}

// In Controller Methods:
if (PermissionHelper::hasPermission('edit-products')) {
    // Allow editing
}

if (PermissionHelper::isAdmin()) {
    // Admin only logic
}
        </pre>
    </div>

    <div class="section">
        <h2>Route Usage Examples</h2>
        <pre>
// In routes/web.php:
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', 'AdminController@index');
});

Route::middleware(['auth', 'permission:view-products'])->group(function () {
    Route::get('/products', 'ProductController@index');
});
        </pre>
    </div>
</body>
</html>











