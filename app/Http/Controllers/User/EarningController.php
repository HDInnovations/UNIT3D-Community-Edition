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
    public function index(Request $request, string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = User::where('username', '=', $username)->sole();

        \abort_unless($request->user()->id == $user->id || $request->user()->group->is_modo, 403);

        $userbon = $user->getSeedbonus();

        // These two partially-built queries are used for constructing all the other queries
        $distinctSeeds = Peer::query()
            ->select(['user_id', 'torrent_id', 'seeder'])
            ->where('user_id', '=', $user->id)
            ->where('seeder', '=', 1)
            ->distinct();

        $history = History::query()
            ->select(['seedtime', 'active', 'user_id'])
            ->where('user_id', '=', $user->id)
            ->where('active', '=', 1);

        $SECONDS_PER_MONTH = 60 * 60 * 24 * 30;

        $dying = $distinctSeeds
            ->clone()
            ->whereHas(
                'torrent',
                fn ($query) => $query
                ->where('seeders', '=', 1)
                ->where('times_completed', '>=', 3)
            )
            ->count();

        $legendary = $distinctSeeds
            ->clone()
            ->whereRelation('torrent', 'created_at', '<', Carbon::now()->subYear()->toDateTimeString())
            ->count();

        $old = $distinctSeeds
            ->clone()
            ->whereHas(
                'torrent',
                fn ($query) => $query
                ->where('created_at', '<', Carbon::now()->subMonths(6)->toDateTimeString())
                ->where('created_at', '>', Carbon::now()->subYear()->toDateTimeString()),
            )
            ->count();

        $huge = $distinctSeeds
            ->clone()
            ->whereRelation('torrent', 'size', '>=', $this->byteUnits->bytesFromUnit('100GiB'))
            ->count();

        $large = $distinctSeeds
            ->clone()
            ->whereHas(
                'torrent',
                fn ($query) => $query
                ->where('size', '>=', $this->byteUnits->bytesFromUnit('25GiB'))
                ->where('size', '<', $this->byteUnits->bytesFromUnit('100GiB'))
            )
            ->count();

        $regular = $distinctSeeds
            ->clone()
            ->whereHas(
                'torrent',
                fn ($query) => $query
                ->where('size', '>=', $this->byteUnits->bytesFromUnit('1GiB'))
                ->where('size', '<', $this->byteUnits->bytesFromUnit('25GiB'))
            )
            ->count();

        $participant = $history
            ->clone()
            ->where('seedtime', '>=', $SECONDS_PER_MONTH)
            ->where('seedtime', '<', $SECONDS_PER_MONTH * 2)
            ->count();

        $teamplayer = $history
            ->clone()
            ->where('seedtime', '>=', $SECONDS_PER_MONTH * 2)
            ->where('seedtime', '<', $SECONDS_PER_MONTH * 3)
            ->count();

        $committed = $history
            ->clone()
            ->where('seedtime', '>=', $SECONDS_PER_MONTH * 3)
            ->where('seedtime', '<', $SECONDS_PER_MONTH * 6)
            ->count();

        $mvp = $history
            ->clone()
            ->where('seedtime', '>=', $SECONDS_PER_MONTH * 6)
            ->where('seedtime', '<', $SECONDS_PER_MONTH * 12)
            ->count();

        $legend = $history
            ->clone()
            ->where('seedtime', '>=', $SECONDS_PER_MONTH * 12)
            ->count();

        //Total points per hour
        $total = 2.00 * $dying
            + 1.50 * $legendary
            + 1.00 * $old
            + 0.75 * $huge
            + 0.50 * $large
            + 0.25 * $regular
            + 0.25 * $participant
            + 0.50 * $teamplayer
            + 0.75 * $committed
            + 1.00 * $mvp
            + 2.00 * $legend;

        return \view('user.earning.index', [
            'user'              => $user,
            'userbon'           => $userbon,
            'dying'             => $dying,
            'legendary'         => $legendary,
            'old'               => $old,
            'huge'              => $huge,
            'large'             => $large,
            'regular'           => $regular,
            'participant'       => $participant,
            'teamplayer'        => $teamplayer,
            'committed'         => $committed,
            'mvp'               => $mvp,
            'legend'            => $legend,
            'total'             => $total,
            'username'          => $username,
        ]);
    }
}
