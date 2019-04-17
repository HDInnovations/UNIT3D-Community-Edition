<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommandController extends Controller
{
    /**
     * Display All Commands.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        abort_unless($user->group->is_owner, 403);

        return view('Staff.commands.index');
    }

    /**
     * Bring Site Into Maintenance Mode.
     *
     * @param Request\ $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maintanceEnable(Request $request)
    {
        $user = auth()->user();
        abort_unless($user->group->is_owner, 403);

        \Artisan::call('down --allow='.$request->ip());

        return redirect()->route('staff.commands.index')
            ->withOutput(trim(\Artisan::output()));
    }

    /**
     * Bring Site Out Of Maintenance Mode.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maintanceDisable()
    {
        $user = auth()->user();
        abort_unless($user->group->is_owner, 403);

        \Artisan::call('up');

        return redirect()->route('staff.commands.index')
            ->withOutput(trim(\Artisan::output()));
    }

    /**
     * Clear Site Cache.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearCache()
    {
        $user = auth()->user();
        abort_unless($user->group->is_owner, 403);

        \Artisan::call('cache:clear');

        return redirect()->route('staff.commands.index')
            ->withOutput(trim(\Artisan::output()));
    }

    /**
     * Clear Site View Cache.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearView()
    {
        $user = auth()->user();
        abort_unless($user->group->is_owner, 403);

        \Artisan::call('view:clear');

        return redirect()->route('staff.commands.index')
            ->withOutput(trim(\Artisan::output()));
    }

    /**
     * Clear Site Routes Cache.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearRoute()
    {
        $user = auth()->user();
        abort_unless($user->group->is_owner, 403);

        \Artisan::call('route:clear');

        return redirect()->route('staff.commands.index')
            ->withOutput(trim(\Artisan::output()));
    }

    /**
     * Clear Site Config Cache.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearConfig()
    {
        $user = auth()->user();
        abort_unless($user->group->is_owner, 403);

        \Artisan::call('config:clear');

        return redirect()->route('staff.commands.index')
            ->withOutput(trim(\Artisan::output()));
    }

    /**
     * Clear All Site Cache At Once.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearAllCache()
    {
        $user = auth()->user();
        abort_unless($user->group->is_owner, 403);

        \Artisan::call('clear:all_cache');

        return redirect()->route('staff.commands.index')
            ->withOutput(trim(\Artisan::output()));
    }

    /**
     * Set All Site Cache At Once.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setAllCache()
    {
        $user = auth()->user();
        abort_unless($user->group->is_owner, 403);

        \Artisan::call('set:all_cache');

        return redirect()->route('staff.commands.index')
            ->withOutput(trim(\Artisan::output()));
    }

    /**
     * Send Test Email To Test Email Configuration.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function testEmail()
    {
        $user = auth()->user();
        abort_unless($user->group->is_owner, 403);

        \Artisan::call('test:email');

        return redirect()->route('staff.commands.index')
            ->withOutput(trim(\Artisan::output()));
    }
}
