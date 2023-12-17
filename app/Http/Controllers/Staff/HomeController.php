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
use App\Models\Group;
use App\Services\Unit3dAnnounce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\SslCertificate\SslCertificate;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\HomeControllerTest
 */
class HomeController extends Controller
{
    /**
     * Display Staff Dashboard.
     *
     * @throws Exception
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        // User Info
        $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));
        $validatingGroup = cache()->rememberForever('validating_group', fn () => Group::where('slug', '=', 'validating')->pluck('id'));

        // SSL Info
        try {
            $certificate = $request->secure() ? SslCertificate::createForHostName(config('app.url')) : '';
        } catch (Exception) {
            $certificate = '';
        }

        // System Information
        $systemInformation = new SystemInformation();

        return view('Staff.dashboard.index', [
            'users' => cache()->remember('dashboard_users', 300, fn () => DB::table('users')
                ->selectRaw('count(*) as total')
                ->selectRaw(sprintf('count(case when group_id = %s then 1 end) as banned', $bannedGroup[0]))
                ->selectRaw(sprintf('count(case when group_id = %s then 1 end) as validating', $validatingGroup[0]))
                ->first()),
            'torrents' => cache()->remember('dashboard_torrents', 300, fn () => DB::table('torrents')
                ->selectRaw('count(*) as total')
                ->selectRaw('count(case when status = 0 then 1 end) as pending')
                ->selectRaw('count(case when status = 1 then 1 end) as approved')
                ->selectRaw('count(case when status = 2 then 1 end) as rejected')
                ->selectRaw('count(case when status = 3 then 1 end) as postponed')
                ->first()),
            'peers' => cache()->remember('dashboard_peers', 300, fn () => DB::table('peers')
                ->selectRaw('count(*) as total')
                ->selectRaw('sum(active = 1) as active')
                ->selectRaw('sum(active = 0) as inactive')
                ->selectRaw('sum(seeder = 0 AND active = 1) as leechers')
                ->selectRaw('sum(seeder = 1 AND active = 1) as seeders')
                ->first()),
            'unsolvedReportsCount'     => DB::table('reports')->where('solved', '=', false)->count(),
            'pendingApplicationsCount' => DB::table('applications')->where('status', '=', 0)->count(),
            'certificate'              => $certificate,
            'uptime'                   => $systemInformation->uptime(),
            'ram'                      => $systemInformation->memory(),
            'disk'                     => $systemInformation->disk(),
            'avg'                      => $systemInformation->avg(),
            'basic'                    => $systemInformation->basic(),
            'file_permissions'         => $systemInformation->directoryPermissions(),
            'externalTrackerStats'     => Unit3dAnnounce::getStats(),
        ]);
    }
}
