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
use App\Models\BlacklistClient;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\GroupControllerTest
 */
class BlacklistClientController extends Controller
{
    /**
     * Display All Blacklisted Groups.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        \abort_unless($user->group->is_modo, 403);

        $clients = BlacklistClient::get()->sortBy('id');

        return \view('Staff.blacklist.clients.index', ['clients' => $clients]);
    }

    /**
     * Edit A group.
     */
    public function edit(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $user = $request->user();
        \abort_unless($user->group->is_modo, 403);

        $date = Carbon::now();
        $client = BlacklistClient::findOrFail($id);

        return \view('Staff.blacklist.clients.edit', ['client' => $client]);
    }

    /**
     * Save a group change.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        \abort_unless($user->group->is_modo, 403);

        $client = BlacklistClient::findOrFail($id);
        $client->name = $request->input('name');
        $client->reason = $request->input('reason');

        $v = \validator($client->toArray(), [
            'name'      => 'required',
            'reason',
        ]);

        if ($v->fails()) {
            return \to_route('staff.blacklists.clients.index')
                ->withErrors($v->errors());
        }

        $client->save();

        return \to_route('staff.blacklists.clients.index')
            ->withSuccess('Group Was Updated Successfully!');
    }

    /**
     * Blacklist Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.blacklist.clients.create');
    }

    /**
     * Store A New Blacklisted Group.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        \abort_unless($user->group->is_admin, 403);

        $client = new BlacklistClient();
        $client->name = $request->input('name');
        $client->reason = $request->input('reason');

        $v = \validator($client->toArray(), [
            'name'     => 'required|unique:blacklist_clients',
            'reason',
        ]);

        if ($v->fails()) {
            return \to_route('staff.blacklists.clients.index')
                ->withErrors($v->errors());
        }

        $client->save();

        return \to_route('staff.blacklists.clients.index')
            ->withSuccess('New Internal Group added!');
    }

    /**
     * Delete A Blacklisted Group.
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        \abort_unless($user->group->is_admin, 403);

        $client = BlacklistClient::findOrFail($id);
        $client->delete();

        return \to_route('staff.blacklists.clients.index')
            ->withSuccess('Group Has Been Removed.');
    }
}
