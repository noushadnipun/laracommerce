<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

// Test route for products with images
Route::get('/test-size-guide', function() {
    try {
        $controller = new App\Http\Controllers\Admin\SizeGuideController();
        $request = new Illuminate\Http\Request();
        $result = $controller->index($request);
        return 'Size Guide Controller works!';
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/test-statistics', function() {
    try {
        $controller = new App\Http\Controllers\Admin\StatisticsController();
        $request = new Illuminate\Http\Request();
        $result = $controller->index($request);
        return 'Statistics Controller works!';
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});


include 'admin.php';
include 'frontend.php';
include 'auth.php';

//Artisan
Route::get('/optimize', function () {
    Artisan::call('optimize');
    return 'Configuration cache cleared! <br/>
            Configuration cached successfully! <br/>
            Route cache cleared! <br/>
            Routes cached successfully! <br/>
            Files cached successfully! <br/>';
});

Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    return 'Configuration cache cleared! <br/>
            Configuration cached successfully! <br/>';
});




//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// SSLCOMMERZ Start
    Route::get('/example1', [App\Http\Controllers\SslCommerzPaymentController::class, 'exampleEasyCheckout']);
    Route::get('/example2', [App\Http\Controllers\SslCommerzPaymentController::class, 'exampleHostedCheckout']);

    Route::post('/pay', [App\Http\Controllers\SslCommerzPaymentController::class, 'index']);
    Route::post('/pay-via-ajax', [App\Http\Controllers\SslCommerzPaymentController::class, 'payViaAjax']);

    //Route::post('/success', [App\Http\Controllers\SslCommerzPaymentController::class, 'success'])
    //->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
    //Route::match(['post','get'], '/fail', [App\Http\Controllers\SslCommerzPaymentController::class, 'fail']);
    //Route::match(['post','get'], '/cancel', [App\Http\Controllers\SslCommerzPaymentController::class, 'cancel']);

    //Route::post('/ipn', [App\Http\Controllers\SslCommerzPaymentController::class, 'ipn']);

//SSLCOMMERZ END

// Menu Example Route
Route::get('/menu-example', [App\Http\Controllers\MenuController::class, 'createMenu']);

// Permission system example
Route::get('/permission-example', function () {
    return view('permission-example');
})->middleware('auth');

// Menu structure test
Route::get('/menu-test', function () {
    $primaryMenu = Menu::getByName('primary');
    $secondaryMenu = Menu::getByName('secondary');
    $footerOne = Menu::getByName('footer-1');
    $footerTwo = Menu::getByName('footer-2');
    
    return response()->json([
        'primary' => $primaryMenu,
        'secondary' => $secondaryMenu,
        'footer1' => $footerOne,
        'footer2' => $footerTwo
    ]);
});

// Products with images test
Route::get('/products-test', function () {
    $products = \App\Models\Product::with(['brand'])->get();

    return view('products-test', compact('products'));
});

// Permission system test
Route::get('/permission-test', function () {
    $user = \App\Models\User::first();
    
    if (!$user) {
        return 'No users found!';
    }
    
    $data = [
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_role_id' => $user->role_id,
        'spatie_roles' => $user->roles->pluck('name')->toArray(),
        'spatie_permissions_count' => $user->getAllPermissions()->count(),
        'spatie_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
        'custom_role' => \App\Helpers\PermissionHelper::getUserRoleName(),
        'custom_permissions' => \App\Helpers\PermissionHelper::getUserPermissions(),
    ];
    
    return response()->json($data, 200, [], JSON_PRETTY_PRINT);
});

// Admin menu test (without authentication)
Route::get('/admin-menu-test', function () {
    $menu = \App\Helpers\MenuHelper::render('admin');
    $mscript = \App\Helpers\MenuHelper::scripts();
    
    return view('admin.layouts.menu', compact('menu', 'mscript'));
});



