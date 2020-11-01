<?php

namespace App\Providers;

use App\Models\Privilege;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PrivilegeServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        try {
            Privilege::get()->map(function ($privilege) {
                Gate::define($privilege->slug, function ($user) use ($privilege) {
                    return $user->hasPrivilegeTo($privilege);
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
            return '<?php } ?>'; //return this endif statement inside php tag
        });

        //@privilege()
        Blade::directive('privilege', function ($privilege) {
            return "<?php if(auth()->check() && auth()->user()->hasPrivilegeTo($privilege)) { ?>"; //return this if statement inside php tag
        });
        //@endpermission
        Blade::directive('endprivilege', function ($perm) {
            return '<?php } ?>'; //return this endif statement inside php tag
        });
    }
}
