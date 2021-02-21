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


include 'admin.php';
include 'frontend.php';
include 'auth.php';

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// SSLCOMMERZ Start
    Route::get('/example1', [App\Http\Controllers\SslCommerzPaymentController::class, 'exampleEasyCheckout']);
    Route::get('/example2', [App\Http\Controllers\SslCommerzPaymentController::class, 'exampleHostedCheckout']);

    Route::post('/pay', [App\Http\Controllers\SslCommerzPaymentController::class, 'index']);
    Route::post('/pay-via-ajax', [App\Http\Controllers\SslCommerzPaymentController::class, 'payViaAjax']);

    //Route::post('/success', [App\Http\Controllers\SslCommerzPaymentController::class, 'success']);
    //Route::post('/fail', [App\Http\Controllers\SslCommerzPaymentController::class, 'fail']);
    //Route::post('/cancel', [App\Http\Controllers\SslCommerzPaymentController::class, 'cancel']);

    //Route::post('/ipn', [App\Http\Controllers\SslCommerzPaymentController::class, 'ipn']);

//SSLCOMMERZ END



