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
use App\Models\Internal;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\GroupControllerTest
 */
class InternalController extends Controller
{
    /**
     * Display All Internal Groups.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        \abort_unless($user->group->is_modo, 403);

        $internals = Internal::all()->sortBy('name');

        return \view('Staff.internals.index', ['internals' => $internals]);
    }

    /**
     * Edit A group.
     *
     * @param \App\Models\UsersVIP $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $user = $request->user();
        \abort_unless($user->group->is_modo, 403);

        $date = Carbon::now();
        $internal = Internal::findOrFail($id);

        return \view('Staff.internals.edit', ['internal' => $internal]);
    }

    /**
     * Save a group change.
     *
     * @param \App\Models\UsersVIP $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        \abort_unless($user->group->is_modo, 403);

        $internal = Internal::findOrFail($id);

        $internal->name = $request->input('name');
        $internal->icon = $request->input('icon');
        $internal->effect = $request->input('effect');

        $v = \validator($internal->toArray(), [
            'name'      => 'required',
            'icon'      => 'required',
            'effect'    => 'required',
        ]);

        if ($v->fails()) {
            return \redirect()->route('staff.internals.index')
                ->withErrors($v->errors());
        }

        $internal->save();

        return \redirect()->route('staff.internals.index')
            ->withSuccess('Internal Group Was Updated Successfully!');
    }

    /**
     * Internal Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.internals.create');
    }

    /**
     * Store A New Internal Group.
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();
        \abort_unless($user->group->is_admin, 403);

        $internal = new Internal();
        $internal->name = $request->input('name');
        $internal->icon = $request->input('icon');
        $internal->effect = $request->input('effect');

        $v = \validator($internal->toArray(), [
            'name'     => 'required|unique:internals',
            'icon',
            'effect',
        ]);

        if ($v->fails()) {
            return \redirect()->route('staff.internals.index')
                ->withErrors($v->errors());
        }

        $internal->save();

        return \redirect()->route('staff.internals.index')
            ->withSuccess('New Internal Group added!');
    }

    /**
     * Delete A Internal Group.
     *
     * @param $commentId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $internal = Internal::findOrFail($id);

        \abort_unless($user->group->is_admin, 403);
        $internal->delete();

        return \redirect()->route('staff.internals.index')
            ->withSuccess('Group Has Been Removed.');
    }
}
