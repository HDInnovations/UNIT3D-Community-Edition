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
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\CommandControllerTest
 */
class CommandController extends Controller
{
    /**
     * Display All Commands.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.command.index');
    }

    /**
     * Bring Site Into Maintenance Mode.
     */
    public function maintanceEnable(): \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
    {
        Artisan::call('down');

        return \to_route('staff.commands.index')
            ->withInfo(\trim(Artisan::output()));
    }

    /**
     * Bring Site Out Of Maintenance Mode.
     */
    public function maintanceDisable(): \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
    {
        Artisan::call('up');

        return \to_route('staff.commands.index')
            ->withInfo(\trim(Artisan::output()));
    }

    /**
     * Clear Site Cache.
     */
    public function clearCache(): \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
    {
        Artisan::call('cache:clear');

        return \to_route('staff.commands.index')
            ->withInfo(\trim(Artisan::output()));
    }

    /**
     * Clear Site View Cache.
     */
    public function clearView(): \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
    {
        Artisan::call('view:clear');

        return \to_route('staff.commands.index')
            ->withInfo(\trim(Artisan::output()));
    }

    /**
     * Clear Site Routes Cache.
     */
    public function clearRoute(): \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
    {
        Artisan::call('route:clear');

        return \to_route('staff.commands.index')
            ->withInfo(\trim(Artisan::output()));
    }

    /**
     * Clear Site Config Cache.
     */
    public function clearConfig(): \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
    {
        Artisan::call('config:clear');

        return \to_route('staff.commands.index')
            ->withInfo(\trim(Artisan::output()));
    }

    /**
     * Clear All Site Cache At Once.
     */
    public function clearAllCache(): \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
    {
        Artisan::call('clear:all_cache');

        return \to_route('staff.commands.index')
            ->withInfo(\trim(Artisan::output()));
    }

    /**
     * Set All Site Cache At Once.
     */
    public function setAllCache(): \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
    {
        Artisan::call('set:all_cache');

        return \to_route('staff.commands.index')
            ->withInfo(\trim(Artisan::output()));
    }

    /**
     * Send Test Email To Test Email Configuration.
     */
    public function testEmail(): \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
    {
        Artisan::call('test:email');

        return \to_route('staff.commands.index')
            ->withInfo(\trim(\str_replace(["\r", "\n", '*'], '', Artisan::output())));
    }
}
