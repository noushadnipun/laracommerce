<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\AdminMiddleware::class,
            \App\Http\Middleware\CustomerMiddleware::class,
            \App\Http\Middleware\EditorMiddleware::class,
        ]);
        
        // Register Spatie middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\SpatieRoleMiddleware::class,
            'permission' => \App\Http\Middleware\SpatiePermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
