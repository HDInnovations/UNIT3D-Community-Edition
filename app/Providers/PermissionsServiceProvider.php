<?php

namespace App\Providers;


use App\Models\RBACPermissions;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot()
    {
        try {
            RBACPermissions::get()->map(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });
        } catch (\Exception $e) {
            report($e);
            return false;
        }


        //Blade directives
        //
        // @role()
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole($role)) { ?>"; //return this if statement inside php tag
        });
        //@endrole
        Blade::directive('endrole', function ($role) {
            return "<?php } ?>"; //return this endif statement inside php tag
        });

        //@permission()
        Blade::directive('permission', function ($perm) {

            return "<?php if(auth()->check() && auth()->user()->hasPermissionTo($perm)) { ?>"; //return this if statement inside php tag
        });
        //@endpermission
        Blade::directive('endpermission', function ($perm) {
            return "<?php } ?>"; //return this endif statement inside php tag
        });

    }
}
