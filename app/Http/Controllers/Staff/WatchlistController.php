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
use App\Http\Requests\Staff\StoreWatchedUserRequest;
use App\Models\User;
use App\Models\Watchlist;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\WatchlistControllerTest
 */
class WatchlistController extends Controller
{
    /**
     * Watchlist.
     */
    final public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.watchlist.index');
    }

    /**
     * Store A New Watched User.
     */
    final public function store(StoreWatchedUserRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('id', '=', $id)->sole();

        Watchlist::create(['user_id' => $user->id, 'staff_id' => $request->user()->id] + $request->validated());

        return \to_route('staff.watchlist.index')
            ->withSuccess('User Successfully Being Watched');
    }

    /**
     * Delete A Watched User.
     *
     * @throws \Exception
     */
    final public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $watchedUser = Watchlist::findOrFail($id);
        $watchedUser->delete();

        return \to_route('staff.watchlist.index')
            ->withSuccess('Successfully Stopped Watching User');
    }
}
