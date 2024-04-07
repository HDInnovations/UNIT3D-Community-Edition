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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Peer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Feature\Http\Controllers\BonusControllerTest
 */
class EarningController extends Controller
{
    /**
     * BonusController Constructor.
     */
    public function __construct(protected \App\Interfaces\ByteUnitsInterface $byteUnits)
    {
    }

    /**
     * Show Bonus Earnings System.
     */
    public function index(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);
    
        // Fetch seeding torrents with their seeder count and size for the user
        $seedingTorrents = Peer::where('user_id', $user->id)
            ->where('seeder', 1)
            ->where('active', 1)
            ->whereRaw('date_sub(created_at, interval 30 minute) < now()')
            ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
            ->select('torrents.id as torrent_id', 'torrents.size', 'torrents.seeders')
            ->get();
    
        // Calculate bonuses
        $totalBonus = 0;
        foreach ($seedingTorrents as $torrent) {
            $totalBonus += $this->calculateBonusForTorrent($torrent->seeders, $torrent->size);
        }
    
        return view('user.earning.index', [
            'user' => $user,
            'totalBonus' => $totalBonus,
            // Include other necessary data for the view
        ]);
    }
    
    /**
     * Calculate bonus points for a single torrent based on seeder scarcity and size.
     *
     * @param int $seederCount The number of seeders for the torrent.
     * @param int $size The size of the torrent in bytes.
     * @return float The calculated bonus points for the torrent.
     */
    protected function calculateBonusForTorrent(int $seederCount, int $size): float
    {
        $seederBonus = $this->calculateSeederBonus($seederCount);
        // Ensure size is at least 1 GB for calculation, converting size to GB
        $sizeInGB = max(1, ceil($this->byteUnits->toGigabytes($size)));

        // Apply logarithmic scaling factor
        $scale = 0.5;
        $sizeMultiplier = log(max(1, $sizeInGB)) + 1; // Ensure a minimum value for multiplier
        $sizeMultiplier = $sizeMultiplier * $scale; // Adjust multiplier by scale

        return $seederBonus * $sizeMultiplier;
    }

    /**
     * Calculate the bonus points based on the number of seeders.
     */
    protected function calculateSeederBonus(int $seederCount): float
    {
        if ($seederCount === 1) {
            return 10.0;
        } elseif ($seederCount === 2) {
            return 5.0;
        } elseif ($seederCount >= 3 && $seederCount <= 5) {
            return 3.0;
        } elseif ($seederCount >= 6 && $seederCount <= 9) {
            return 2.0;
        } elseif ($seederCount >= 10 && $seederCount <= 19) {
            return 1.5;
        } elseif ($seederCount >= 20 && $seederCount <= 35) {
            return 1;
        } elseif ($seederCount >= 36 && $seederCount <= 49) {
            return 0.75;
        }

        return 0.5; // Base amount for 50 or more seeders
    }
}
