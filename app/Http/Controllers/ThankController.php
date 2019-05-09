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

use Illuminate\Http\Request;
use App\Models\Thank;
use App\Models\Torrent;

class ThankController extends Controller
{
    /**
     * Thank A Torrent Uploader.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function torrentThank(Request $request, $slug, $id)
    {
        $user = $request->user();
        $torrent = Torrent::findOrFail($id);

        $thank = Thank::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first();
        if ($thank) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->withErrors('You Have Already Thanked On This Torrent!');
        }

        $thank = new Thank();
        $thank->user_id = $user->id;
        $thank->torrent_id = $torrent->id;
        $thank->save();

        //Notification
        if ($user->id != $torrent->user_id) {
            $torrent->notifyUploader('thank', $thank);
        }

        return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
            ->withSuccess('Your Thank Was Successfully Applied!');
    }
}
