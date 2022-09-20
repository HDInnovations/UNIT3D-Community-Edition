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
use App\Models\BonEarning;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $bonEarnings = BonEarning::with('conditions')
            ->orderBy('position')
            ->get()
            ->map(function ($bonEarning) use ($user) {
                $query = DB::table('peers')
                    ->select([
                        DB::raw('1 as `1`'),
                        DB::raw('TIMESTAMPDIFF(SECOND, torrents.created_at, NOW()) as age'),
                        'torrents.size',
                        'torrents.seeders',
                        'torrents.leechers',
                        'torrents.times_completed',
                        DB::raw('max(history.seedtime) as seedtime'),
                        'torrents.personal_release',
                        'torrents.internal',
                        DB::raw('max(peers.connectable) as connectable'),
                        'peers.torrent_id',
                        'peers.user_id',
                    ])
                    ->join('history', fn ($join) => $join->on('history.torrent_id', '=', 'peers.torrent_id')->where('history.user_id', '=', 'peers.user_id'))
                    ->join('torrents', 'peers.torrent_id', '=', 'torrents.id')
                    ->where('peers.seeder', '=', true)
                    ->where('peers.active', '=', true)
                    ->where('peers.user_id', '=', $user->id)
                    ->groupBy(['peers.torrent_id', 'peers.user_id']);

                foreach ($bonEarning->conditions as $condition) {
                    // Validate raw values
                    if (\in_array($condition->operand1, [
                        '1',
                        'age',
                        'size',
                        'seeders',
                        'leechers',
                        'times_completed',
                        'seedtime',
                        'personal_release',
                        'internal',
                        'connectable',
                    ], true)) {
                        $query->having(DB::raw('`'.$condition->operand1.'`'), $condition->operator, $condition->operand2);
                    }
                }

                $peers = $query->get();

                $userEarnings = [];

                switch ($bonEarning->operation) {
                    case 'append':
                        foreach ($peers as $peer) {
                            @$userEarnings[$peer->torrent_id] += $peer->{$bonEarning->variable} * $bonEarning->multiplier;
                        }

                        break;
                    case 'multiply':
                        foreach ($peers as $peer) {
                            @$userEarnings[$peer->torrent_id] *= $peer->{$bonEarning->variable} * $bonEarning->multiplier;
                        }

                        break;
                }

                $bonEarning->setRelation('user_earnings', array_sum($userEarnings));
                $bonEarning->setRelation('torrent_count', $peers->count());

                return $bonEarning;
            });

        return view('user.earning.index', [
            'user'        => $user,
            'bon'         => $user->formatted_seedbonus,
            'bonEarnings' => $bonEarnings,
        ]);
    }
}
