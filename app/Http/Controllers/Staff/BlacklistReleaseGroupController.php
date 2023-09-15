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
use App\Models\Type;
use Carbon\Carbon;
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
        abort_unless($user->group->is_modo, 403);

        $releasegroups = BlacklistReleaseGroup::get()->sortBy('name');

        return view('Staff.blacklist.releasegroups.index', [
            'releasegroups' => $releasegroups,
            'types'         => Type::select(['id', 'name', 'position'])->where('id', '<', 10)->orderBy('position')->get(),
        ]);
    }

    /**
     * Edit A group.
     */
    public function edit(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $user = $request->user();
        abort_unless($user->group->is_modo, 403);

        $date = Carbon::now();
        $releasegroup = BlacklistReleaseGroup::findOrFail($id);

        return view('Staff.blacklist.releasegroups.edit', [
            'releasegroup' => $releasegroup,
            'types'        => Type::select(['id', 'name', 'position'])->where('id', '<', 10)->orderBy('position')->get(),
        ]);
    }

    /**
     * Save a group change.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->group->is_modo, 403);

        $releasegroup = BlacklistReleaseGroup::findOrFail($id);
        $releasegroup->name = $request->input('name');
        $releasegroup->reason = $request->input('reason');

        $v = validator($request->all(), [
            'name' => 'required',
            'reason',
            'types'   => 'sometimes|array|max:999',
            'types.*' => 'sometimes|exists:types,id',
        ]);

        $params = $request->only([
            'types',
        ]);

        if ($v->fails()) {
            return to_route('staff.blacklisted_releasegroups.index')
                ->withErrors($v->errors());
        }

        $releasegroup->json_types = $params;
        $releasegroup->save();

        return to_route('staff.blacklisted_releasegroups.index')
            ->withSuccess('Group Was Updated Successfully!');
    }

    /**
     * Blacklist Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.blacklist.releasegroups.create', [
            'types' => Type::select(['id', 'name', 'position'])->where('id', '<', 10)->orderBy('position')->get(),
        ]);
    }

    /**
     * Store A New Blacklisted Group.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->group->is_modo, 403);

        $releasegroup = new BlacklistReleaseGroup();
        $releasegroup->name = $request->input('name');
        $releasegroup->reason = $request->input('reason');

        $v = validator($request->all(), [
            'name'    => 'required|unique:blacklist_releasegroups|max:100',
            'reason'  => 'max:255',
            'types'   => 'sometimes|array|max:999',
            'types.*' => 'sometimes|exists:types,id',
        ]);

        $params = $request->only([
            'types',
        ]);

        if ($v->fails()) {
            return to_route('staff.blacklisted_releasegroups.index')
                ->withErrors($v->errors());
        }

        $releasegroup->json_types = $params;
        $releasegroup->save();

        return to_route('staff.blacklisted_releasegroups.index')
            ->withSuccess('New Internal Group added!');
    }

    /**
     * Delete A Blacklisted Group.
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->group->is_modo, 403);

        $releasegroup = BlacklistReleaseGroup::findOrFail($id);
        $releasegroup->delete();

        return to_route('staff.blacklisted_releasegroups.index')
            ->withSuccess('Group Has Been Removed.');
    }
}
