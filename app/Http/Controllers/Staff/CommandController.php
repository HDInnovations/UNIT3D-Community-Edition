<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Artisan;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

final class CommandController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;

    public function __construct(Factory $viewFactory, Redirector $redirector)
    {
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
    }

    /**
     * Display All Commands.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request): Factory
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        return $this->viewFactory->make('Staff.command.index');
    }

    /**
     * Bring Site Into Maintenance Mode.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maintanceEnable(Request $request): Factory
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        Artisan::call('down --allow='.$request->ip());

        return $this->redirector->route('staff.commands.index')
            ->withInfo(trim(Artisan::output()));
    }

    /**
     * Bring Site Out Of Maintenance Mode.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maintanceDisable(Request $request): Factory
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        Artisan::call('up');

        return $this->redirector->route('staff.commands.index')
            ->withInfo(trim(Artisan::output()));
    }

    /**
     * Clear Site Cache.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearCache(Request $request): Factory
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        Artisan::call('cache:clear');

        return $this->redirector->route('staff.commands.index')
            ->withInfo(trim(Artisan::output()));
    }

    /**
     * Clear Site View Cache.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearView(Request $request): Factory
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        Artisan::call('view:clear');

        return $this->redirector->route('staff.commands.index')
            ->withInfo(trim(Artisan::output()));
    }

    /**
     * Clear Site Routes Cache.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearRoute(Request $request): Factory
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        Artisan::call('route:clear');

        return $this->redirector->route('staff.commands.index')
            ->withInfo(trim(Artisan::output()));
    }

    /**
     * Clear Site Config Cache.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearConfig(Request $request): Factory
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        Artisan::call('config:clear');

        return $this->redirector->route('staff.commands.index')
            ->withInfo(trim(Artisan::output()));
    }

    /**
     * Clear All Site Cache At Once.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearAllCache(Request $request): Factory
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        Artisan::call('clear:all_cache');

        return $this->redirector->route('staff.commands.index')
            ->withInfo(trim(Artisan::output()));
    }

    /**
     * Set All Site Cache At Once.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setAllCache(Request $request): Factory
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        Artisan::call('set:all_cache');

        return $this->redirector->route('staff.commands.index')
            ->withInfo(trim(Artisan::output()));
    }

    /**
     * Send Test Email To Test Email Configuration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function testEmail(Request $request): Factory
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        Artisan::call('test:email');

        return $this->redirector->route('staff.commands.index')
            ->withInfo(trim(Artisan::output()));
    }
}
