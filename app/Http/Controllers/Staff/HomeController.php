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

namespace App\Http\Controllers\Staff;

use App\Models\Peer;
use App\Models\User;
use App\Models\Group;
use App\Models\Client;
use App\Models\Report;
use App\Models\Torrent;
use App\Models\Application;
use App\Helpers\SystemInformation;
use App\Http\Controllers\Controller;
use Spatie\SslCertificate\SslCertificate;

class HomeController extends Controller
{
    /**
     * Staff Dashboard Index.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        // User Info
        $bannedGroup = Group::where('slug', '=', 'banned')->select('id')->first();
        $validatingGroup = Group::where('slug', '=', 'validating')->select('id')->first();

        $num_user = User::count();
        $banned = User::where('group_id', '=', $bannedGroup->id)->count();
        $validating = User::where('group_id', '=', $validatingGroup->id)->count();

        // Torrent Info
        $num_torrent = Torrent::count();
        $pending = Torrent::pending()->count();
        $rejected = Torrent::rejected()->count();

        // Peers Info
        $peers = Peer::count();
        $seeders = Peer::where('seeder', '=', 1)->count();
        $leechers = Peer::where('seeder', '=', 0)->count();

        // Seedbox Info
        $seedboxes = Client::count();
        $highspeed_users = Client::count();
        $highspeed_torrents = Torrent::where('highspeed', '=', 1)->count();

        // User Info
        $reports = Report::count();
        $unsolved = Report::where('solved', '=', 0)->count();
        $solved = Report::where('solved', '=', 1)->count();

        // SSL Info
        try {
            $certificate = request()->secure() ? SslCertificate::createForHostName(config('app.url')) : '';
        } catch (\Exception $e) {
            $certificate = '';
        }

        // System Information
        $sys = new SystemInformation();
        $uptime = $sys->uptime();
        $ram = $sys->memory();
        $disk = $sys->disk();
        $avg = $sys->avg();
        $basic = $sys->basic();

        // Pending Applications Count
        $app_count = Application::pending()->count();

        return view('Staff.home.index', [
            'num_user'           => $num_user,
            'banned'             => $banned,
            'validating'         => $validating,
            'num_torrent'        => $num_torrent,
            'pending'            => $pending,
            'rejected'           => $rejected,
            'peers'              => $peers,
            'seeders'            => $seeders,
            'leechers'           => $leechers,
            'seedboxes'          => $seedboxes,
            'highspeed_users'    => $highspeed_users,
            'highspeed_torrents' => $highspeed_torrents,
            'reports'            => $reports,
            'unsolved'           => $unsolved,
            'solved'             => $solved,
            'certificate'        => $certificate,
            'uptime'             => $uptime,
            'ram'                => $ram,
            'disk'               => $disk,
            'avg'                => $avg,
            'basic'              => $basic,
            'app_count'          => $app_count,
        ]);
    }
}
