<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\PermissionHelper;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Blade directive for role checking
        Blade::directive('role', function ($role) {
            return "<?php if(App\Helpers\PermissionHelper::hasRole($role)): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        // Blade directive for permission checking
        Blade::directive('permission', function ($permission) {
            return "<?php if(App\Helpers\PermissionHelper::hasPermission($permission)): ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });

        // Blade directive for any role checking
        Blade::directive('anyrole', function ($roles) {
            return "<?php if(App\Helpers\PermissionHelper::hasAnyRole($roles)): ?>";
        });

        Blade::directive('endanyrole', function () {
            return "<?php endif; ?>";
        });

        // Blade directive for any permission checking
        Blade::directive('anypermission', function ($permissions) {
            return "<?php if(App\Helpers\PermissionHelper::hasAnyPermission($permissions)): ?>";
        });

        Blade::directive('endanypermission', function () {
            return "<?php endif; ?>";
        });
    }
}











