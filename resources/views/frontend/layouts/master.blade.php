<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>
        @yield('site-title')
    </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="wishlist-toggle-url" content="{{ route('frontend_wishlist_toggle') }}">
    <meta name="compare-add-url" content="{{ route('frontend_compare_add') }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    
    <!-- CSS  ========================= -->
    @include('frontend.layouts.css')
    @stack('styles')

</head>

<body>
   
    
    <div class="off_canvars_overlay">
              
    </div>
    @include('frontend.layouts.notification')
    @include('frontend.layouts.header')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @yield('page-content')
        </div>
    </div>
    @include('frontend.layouts.footer')

    <!-- JS   ========================================= -->

    @include('frontend.layouts.js')
    @yield('cusjs')
    @stack('scripts')

</body>
</html>