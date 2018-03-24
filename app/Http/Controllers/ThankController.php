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

use App\Thank;
use App\Torrent;
use \Toastr;

class ThankController extends Controller
{
    /**
     * Thank A Torrent Uploader
     *
     * @access public
     * @return back
     *
     */
    public function torrentThank($slug, $id)
    {
        $user = auth()->user();
        $torrent = Torrent::findOrFail($id);

        $thank = Thank::where('user_id', $user->id)->where('torrent_id', $torrent->id)->first();
        if ($thank) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::error('You Have Already Thanked On This Torrent!', 'Whoops!', ['options']));
        }

        $thank = new Thank();
        $thank->user_id = $user->id;
        $thank->torrent_id = $torrent->id;
        $thank->save();

        return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::success('Your Thank Was Successfully Applied!', 'Yay!', ['options']));
    }
}
