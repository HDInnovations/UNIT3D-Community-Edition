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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Torrent;
use App\Peer;
use App\User;
use App\Client;
use App\Report;
use App\Poll;
use \Toastr;

class HomeController extends Controller
{
    /**
     * Staff dashboard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        //User Info
        $num_user = User::all()->count();
        $banned = User::where('group_id', 5)->count();
        $validating = User::where('group_id', 1)->count();

        //Torrent Info
        $num_torrent = Torrent::all()->count();
        $pending = Torrent::pending()->count();
        $rejected = Torrent::rejected()->count();

        //Peers Info
        $peers = Peer::all()->count();
        $seeders = Peer::where('seeder', 1)->count();
        $leechers = Peer::where('seeder', 0)->count();

        //Seedbox Info
        $seedboxes = Client::all()->count();
        $highspeed_users = Client::all()->count();
        $highspeed_torrents = Torrent::where('highspeed', 1)->count();

        //User Info
        $reports = Report::all()->count();
        $unsolved = Report::where('solved', 0)->count();
        $solved = Report::where('solved', 1)->count();

        //Polls
        $pollCount = Poll::count();

        return view('Staff.home.index', ['num_user' => $num_user, 'banned' => $banned, 'validating' => $validating,
            'num_torrent' => $num_torrent, 'pending' => $pending, 'rejected' => $rejected, 'peers' => $peers,
            'seeders' => $seeders, 'leechers' => $leechers, 'seedboxes' => $seedboxes,
            'highspeed_users' => $highspeed_users, 'highspeed_torrents' => $highspeed_torrents, 'reports' => $reports,
            'unsolved' => $unsolved, 'solved' => $solved, 'pollCount' => $pollCount]);
    }
}
