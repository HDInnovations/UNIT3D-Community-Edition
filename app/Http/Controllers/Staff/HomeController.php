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
use App\Group;
use App\Helpers\SystemInformation;
use Spatie\SslCertificate\SslCertificate;

class HomeController extends Controller
{
    /**
     * Staff Dashboard Index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        // User Info
        $bannedGroup = Group::where('slug', '=', 'banned')->pluck('id');
        $validatingGroup = Group::where('slug', '=', 'validating')->pluck('id');

        $num_user = User::count();
        $banned = User::where('group_id', $bannedGroup)->count();
        $validating = User::where('group_id', $validatingGroup)->count();

        // Torrent Info
        $num_torrent = Torrent::count();
        $pending = Torrent::pending()->count();
        $rejected = Torrent::rejected()->count();

        // Peers Info
        $peers = Peer::count();
        $seeders = Peer::where('seeder', 1)->count();
        $leechers = Peer::where('seeder', 0)->count();

        // Seedbox Info
        $seedboxes = Client::count();
        $highspeed_users = Client::count();
        $highspeed_torrents = Torrent::where('highspeed', 1)->count();

        // User Info
        $reports = Report::count();
        $unsolved = Report::where('solved', 0)->count();
        $solved = Report::where('solved', 1)->count();

        // SSL Info
        $certificate = SslCertificate::createForHostName(config('app.url'));

        // System Information
        $sys = new SystemInformation();
        $uptime = $sys->uptime();
        $ram = $sys->memory();
        $disk = $sys->disk();
        $avg = $sys->avg();
        $basic = $sys->basic();

        return view('Staff.home.index', [
            'num_user' => $num_user,
            'banned' => $banned,
            'validating' => $validating,
            'num_torrent' => $num_torrent,
            'pending' => $pending,
            'rejected' => $rejected,
            'peers' => $peers,
            'seeders' => $seeders,
            'leechers' => $leechers,
            'seedboxes' => $seedboxes,
            'highspeed_users' => $highspeed_users,
            'highspeed_torrents' => $highspeed_torrents,
            'reports' => $reports,
            'unsolved' => $unsolved,
            'solved' => $solved,
            'certificate' => $certificate,
            'uptime' => $uptime,
            'ram' => $ram,
            'disk' => $disk,
            'avg' => $avg,
            'basic' => $basic
        ]);
    }
}
