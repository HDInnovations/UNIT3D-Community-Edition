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
use Illuminate\Http\Request;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\GroupControllerTest
 */
class BlacklistClientController extends Controller
{
    /**
     * Display All Blacklisted Clients.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        \abort_unless($request->user()->group->is_modo, 403);

        $clients = BlacklistClient::latest()->get();

        return \view('Staff.blacklist.clients.index', ['clients' => $clients]);
    }

    /**
     * Blacklisted Client Edit Form.
     */
    public function edit(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        \abort_unless($request->user()->group->is_modo, 403);

        $client = BlacklistClient::findOrFail($id);

        return \view('Staff.blacklist.clients.edit', ['client' => $client]);
    }

    /**
     * Edit A Blacklisted Client.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        \abort_unless($request->user()->group->is_modo, 403);

        $client = BlacklistClient::findOrFail($id);
        $client->name = $request->input('name');
        $client->reason = $request->input('reason');

        $v = \validator($client->toArray(), [
            'name'   => 'required|string',
            'reason' => 'sometimes|string',
        ]);

        if ($v->fails()) {
            return \to_route('staff.blacklists.clients.index')
                ->withErrors($v->errors());
        }

        $client->save();

        return \to_route('staff.blacklists.clients.index')
            ->withSuccess('Blacklisted Client Was Updated Successfully!');
    }

    /**
     * Blacklisted Client Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.blacklist.clients.create');
    }

    /**
     * Store A New Blacklisted Client.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        \abort_unless($request->user()->group->is_admin, 403);

        $client = new BlacklistClient();
        $client->name = $request->input('name');
        $client->reason = $request->input('reason');

        $v = \validator($client->toArray(), [
            'name'   => 'required|string|unique:blacklist_clients',
            'reason' => 'sometimes|string',
        ]);

        if ($v->fails()) {
            return \to_route('staff.blacklists.clients.index')
                ->withErrors($v->errors());
        }

        $client->save();

        return \to_route('staff.blacklists.clients.index')
            ->withSuccess('Blacklisted Client Stored Successfully!');
    }

    /**
     * Delete A Blacklisted Client.
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        \abort_unless($request->user()->group->is_admin, 403);

        $client = BlacklistClient::findOrFail($id);
        $client->delete();

        return \to_route('staff.blacklists.clients.index')
            ->withSuccess('Blacklisted Client Destroyed Successfully!');
    }
}
