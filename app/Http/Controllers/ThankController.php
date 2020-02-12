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

use App\Models\Thank;
use App\Models\Torrent;
use Illuminate\Http\Request;

class ThankController extends Controller
{
    /**
     * Store A New Thank.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $id)
    {
        $user = $request->user();
        $torrent = Torrent::findOrFail($id);

        if ($user->id === $torrent->user_id) {
            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('You Cannot Thank Your Own Torrent!');
        }

        $thank = Thank::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first();
        if ($thank) {
            return redirect()->route('torrent', ['id' => $torrent->id])
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

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Your Thank Was Successfully Applied!');
    }
}
