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

namespace App\Http\Controllers\Staff;

use App\Helpers\SystemInformation;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Group;
use App\Models\Peer;
use App\Models\Report;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\SslCertificate\SslCertificate;

class HomeController extends Controller
{
    /**
     * Display Staff Dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // User Info
        $banned_group = cache()->rememberForever('banned_group', function () {
            return Group::where('slug', '=', 'banned')->pluck('id');
        });
        $validating_group = cache()->rememberForever('validating_group', function () {
            return Group::where('slug', '=', 'validating')->pluck('id');
        });
        $num_user = User::count();
        $banned = User::where('group_id', '=', $banned_group[0])->count();
        $validating = User::where('group_id', '=', $validating_group[0])->count();

        // Torrent Info
        $num_torrent = Torrent::count();
        $pending = Torrent::pending()->count();
        $rejected = Torrent::rejected()->count();

        // Peers Info
        $peers = Peer::count();
        $seeders = Peer::where('seeder', '=', 1)->count();
        $leechers = Peer::where('seeder', '=', 0)->count();

        // Reports Info
        $reports_count = Report::where('solved', '=', 0)->count();

        // SSL Info
        try {
            $certificate = $request->secure() ? SslCertificate::createForHostName(config('app.url')) : '';
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

        // Directory Permissions
        $file_permissions = $sys->directoryPermissions();

        // Pending Applications Count
        $app_count = Application::pending()->count();

        return view('Staff.dashboard.index', [
            'num_user'           => $num_user,
            'banned'             => $banned,
            'validating'         => $validating,
            'num_torrent'        => $num_torrent,
            'pending'            => $pending,
            'rejected'           => $rejected,
            'peers'              => $peers,
            'seeders'            => $seeders,
            'leechers'           => $leechers,
            'reports_count'      => $reports_count,
            'certificate'        => $certificate,
            'uptime'             => $uptime,
            'ram'                => $ram,
            'disk'               => $disk,
            'avg'                => $avg,
            'basic'              => $basic,
            'file_permissions'   => $file_permissions,
            'app_count'          => $app_count,
        ]);
    }
}
