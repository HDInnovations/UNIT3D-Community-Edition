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
use App\Models\BlacklistReleaseGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\GroupControllerTest
 */
class BlacklistReleaseGroupController extends Controller
{
    /**
     * Display All Blacklisted Groups.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        \abort_unless($user->group->is_modo, 403);

        $releasegroups = BlacklistReleaseGroup::get()->sortBy('id');

        return \view('Staff.blacklist.releasegroups.index', ['releasegroups' => $releasegroups]);
    }

    /**
     * Edit A group.
     */
    public function edit(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $user = $request->user();
        \abort_unless($user->group->is_modo, 403);

        $date = Carbon::now();
        $releasegroup = BlacklistReleaseGroup::findOrFail($id);

        return \view('Staff.blacklist.releasegroups.edit', ['releasegroup' => $releasegroup]);
    }

    /**
     * Save a group change.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        \abort_unless($user->group->is_modo, 403);

        $releasegroup = BlacklistReleaseGroup::findOrFail($id);
        $releasegroup->name = $request->input('name');
        $releasegroup->reason = $request->input('reason');

        $v = \validator($releasegroup->toArray(), [
            'name'      => 'required',
            'reason',
        ]);

        if ($v->fails()) {
            return \to_route('staff.blacklists.releasegroups.index')
                ->withErrors($v->errors());
        }

        $releasegroup->save();

        return \to_route('staff.blacklists.releasegroups.index')
            ->withSuccess('Group Was Updated Successfully!');
    }

    /**
     * Blacklist Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.blacklist.releasegroups.create');
    }

    /**
     * Store A New Blacklisted Group.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        \abort_unless($user->group->is_admin, 403);

        $releasegroup = new BlacklistReleaseGroup();
        $releasegroup->name = $request->input('name');
        $releasegroup->reason = $request->input('reason');
        
        $v = \validator($releasegroup->toArray(), [
            'name'     => 'required|unique:blacklist_releasegroups',
            'reason',
        ]);

        if ($v->fails()) {
            return \to_route('staff.blacklists.releasegroups.index')
                ->withErrors($v->errors());
        }

        $releasegroup->save();

        return \to_route('staff.blacklists.releasegroups.index')
            ->withSuccess('New Internal Group added!');
    }

    /**
     * Delete A Blacklisted Group.
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        \abort_unless($user->group->is_admin, 403);

        $releasegroup = BlacklistReleaseGroup::findOrFail($id);
        $releasegroup->delete();

        return \to_route('staff.blacklists.releasegroups.index')
            ->withSuccess('Group Has Been Removed.');
    }
}
