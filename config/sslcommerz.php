<?php
//Custom Clas Define AppServivce Provider
// SSLCommerz configuration
$appDomain = \App\Models\StoreSettings::select('meta_value')->where('meta_name', 'ssl_sandbox_live')->first()->meta_value == 'live' ? 'https://securepay.sslcommerz.com' : 'https://sandbox.sslcommerz.com';
$storeID = \App\Models\StoreSettings::select('meta_value')->where('meta_name', 'ssl_store_id')->first()->meta_value;
$storePass = \App\Models\StoreSettings::select('meta_value')->where('meta_name', 'ssl_store_password')->first()->meta_value;
$getConnect = \App\Models\StoreSettings::select('meta_value')->where('meta_name', 'ssl_sandbox_live')->first()->meta_value == 'live' ? false : true;
return [
    'projectPath' => env('PROJECT_PATH'),
    // For Sandbox, use "https://sandbox.sslcommerz.com"
    // For Live, use "https://securepay.sslcommerz.com"
    //'apiDomain' => env("API_DOMAIN_URL", "https://sandbox.sslcommerz.com"),
    'apiDomain' => $appDomain,
    'apiCredentials' => [
        //'store_id' => env("STORE_ID"),
        //'store_password' => env("STORE_PASSWORD"),
        'store_id' => $storeID,
        'store_password' => $storePass,
    ],
    'apiUrl' => [
        'make_payment' => "/gwprocess/v4/api.php",
        'transaction_status' => "/validator/api/merchantTransIDvalidationAPI.php",
        'order_validate' => "/validator/api/validationserverAPI.php",
        'refund_payment' => "/validator/api/merchantTransIDvalidationAPI.php",
        'refund_status' => "/validator/api/merchantTransIDvalidationAPI.php",
    ],
    //'connect_from_localhost' => env("IS_LOCALHOST", true), // For Sandbox, use "true", For Live, use "false"
    'connect_from_localhost' => env("IS_LOCALHOST", $getConnect), // For Sandbox, use "true", For Live, use "false"
    'success_url' => '/success',
    'failed_url' => '/fail',
    'cancel_url' => '/cancel',
    'ipn_url' => '/ipn',
];
