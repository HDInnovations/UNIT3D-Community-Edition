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

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\PersonalFreeleech;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Http\Request;

final class BookmarkController extends Controller
{
    /**
     * Display All Bookmarks.
     *
     * @param Request $request
     * @param $username
     *
     * @return Factory|View
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
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
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
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $request->user()->bookmarks()->detach($torrent->id);

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent Has Been Unbookmarked Successfully!');
    }
}
