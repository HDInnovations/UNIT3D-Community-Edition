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

namespace App\Http\Controllers;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * User Roles Index.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user()->with('history');

        $hidden = [
            'Pruned',
            'Banned',
            'Disabled',
            'Guest',
            'Validating',
            'Bot',
        ];

        $roles = Role::whereNotIn('name', $hidden)->orderBy('position', 'desc')->get();

        $current = Carbon::now();
        $year = 31_536_000;
        $month = 2_592_000;
        $week = 604_800;
        $day = 86_400;
        $hour = 3_600;
        $minute = 60;

        return view('role.index', [
            'user'    => $user,
            'roles'   => $roles,
            'current' => $current,
            'year'    => $year,
            'month'   => $month,
            'week'    => $week,
            'day'     => $day,
            'hour'    => $hour,
            'minut'   => $minute,
        ]);
    }
}
