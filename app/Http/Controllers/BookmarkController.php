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

use App\Models\Torrent;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @see \Tests\Feature\Http\Controllers\BookmarkControllerTest
 */
class BookmarkController extends Controller
{
    /**
     * Display All Bookmarks.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $username)
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless(($request->user()->id == $user->id), 403);

        return \view('bookmark.index', ['user' => $user]);
    }

    /**
     * Store A New Bookmark.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        if ($request->user()->isBookmarked($torrent->id)) {
            return \redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('Torrent has already been bookmarked.');
        }
        $request->user()->bookmarks()->attach($torrent->id);

        return \redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent Has Been Bookmarked Successfully!');
    }

    /**
     * Delete A Bookmark.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $request->user()->bookmarks()->detach($torrent->id);

        return \redirect()->back()
            ->withSuccess('Torrent Has Been Unbookmarked Successfully!');
    }
}
