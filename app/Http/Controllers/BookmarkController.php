<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Bookmark;
use App\Torrent;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

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
        $myBookmarks = Auth::user()->bookmarks;

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
        Auth::user()->bookmarks()->detach($id);
        return back()->with(Toastr::info('Torrent Has Been Unbookmarked Successfully!', 'Info', ['options']));
    }

}
