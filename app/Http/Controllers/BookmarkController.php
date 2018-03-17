<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use \Toastr;

class BookmarkController extends Controller
{

    /**
     * Bookmarks
     *
     *
     * @access public
     * @return view::make bookmark.bookmarks
     */
    public function bookmarks()
    {
        $myBookmarks = auth()->user()->bookmarks;

        return view('bookmark.bookmarks', ['myBookmarks' => $myBookmarks]);
    }

    /**
     * unBookmark a particular torrent
     *
     *
     * @return Response
     */
    public function unBookmark($id)
    {
        auth()->user()->bookmarks()->detach($id);
        return redirect()->back()->with(Toastr::success('Torrent Has Been Unbookmarked Successfully!', 'Yay!', ['options']));
    }
}
