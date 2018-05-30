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

use Illuminate\Http\Request;
use App\Torrent;
use App\Graveyard;
use Carbon\Carbon;
use \Toastr;

class GraveyardController extends Controller
{

    /**
     * Show The Graveyard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        $dead = Torrent::where('seeders', 0)->latest('leechers')->paginate(50);
        $deadcount = Torrent::where('seeders', 0)->count();
        $time = config('graveyard.time');
        $tokens = config('graveyard.reward');

        return view('graveyard.index', ['user' => $user, 'dead' => $dead,
            'deadcount' => $deadcount, 'time' => $time, 'tokens' => $tokens]);
    }

    /**
     * Resurrect A Torrent
     *
     * @param Request $request
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function resurrect(Request $request, $id)
    {
        $user = auth()->user();
        $torrent = Torrent::findOrFail($id);
        $resurrected = Graveyard::where('torrent_id', $torrent->id)->first();

        if ($resurrected) {
            return redirect()->route('graveyard')
                ->with(Toastr::error('Torrent Resurrection Failed! This torrent is already pending a resurrection.', 'Whoops!', ['options']));
        }

        if ($user->id === $torrent->user_id) {
            return redirect()->route('graveyard')
                ->with(Toastr::error('Torrent Resurrection Failed! You cannot resurrect your own uploads.', 'Whoops!', ['options']));
        }

        $resurrection = new Graveyard();
        $resurrection->user_id = $user->id;
        $resurrection->torrent_id = $torrent->id;
        $resurrection->seedtime = $request->input('seedtime');

        $v = validator($resurrection->toArray(), [
            'user_id' => 'required',
            'torrent_id' => 'required',
            'seedtime' => 'required'
        ]);

        if ($v->fails()) {
            return redirect()->route('graveyard')
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $resurrection->save();
            return redirect()->route('graveyard')
                ->with(Toastr::success('Torrent Resurrection Complete! You will be rewarded automatically once seedtime requirements are met.', 'Yay!', ['options']));
        }
    }
}
