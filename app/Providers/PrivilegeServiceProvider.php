<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @credits    clandestine8 <https://github.com/clandestine8>
 */

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
            return "<?php if(auth()->check() && auth()->user()->hasRole([$role])) { ?>"; //return this if statement inside php tag
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
