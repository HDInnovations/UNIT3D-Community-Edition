<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use App\Models\PersonalFreeleech;

class BookmarkController extends Controller
{
    /**
     * Get Torrent Bookmarks.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bookmarks(Request $request)
    {
        $user = $request->user();
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $bookmarks = $user->bookmarks()->paginate(25);

        return view('bookmark.bookmarks', [
            'user'               => $user,
            'personal_freeleech' => $personal_freeleech,
            'bookmarks'          => $bookmarks,
        ]);
    }
}
