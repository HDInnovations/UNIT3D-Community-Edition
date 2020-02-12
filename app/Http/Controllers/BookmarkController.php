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

use App\Models\PersonalFreeleech;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * Display All Bookmarks.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $username)
    {
        $user = User::with('bookmarks')->where('username', '=', $username)->firstOrFail();

        abort_unless(($request->user()->group->is_modo || $request->user()->id == $user->id), 403);

        $bookmarks = $user->bookmarks()->latest()->paginate(25);
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();

        return view('user.bookmarks', [
            'user'               => $user,
            'personal_freeleech' => $personal_freeleech,
            'bookmarks'          => $bookmarks,
            'route'              => 'bookmarks.index',
        ]);
    }

    /**
     * Store A New Bookmark.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        if ($request->user()->isBookmarked($torrent->id)) {
            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('Torrent has already been bookmarked.');
        }
        $request->user()->bookmarks()->attach($torrent->id);

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent Has Been Bookmarked Successfully!');
    }

    /**
     * Delete A Bookmark.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $request->user()->bookmarks()->detach($torrent->id);

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent Has Been Unbookmarked Successfully!');
    }
}
