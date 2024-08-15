<?php

declare(strict_types=1);

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

namespace App\Console\Commands;

use App\Models\Peer;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class AutoStatsClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:stats_clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the Client Stats daily.';

    /**
     * Execute the console command.
     *
     * @throws Exception|Throwable If there is an error during the execution of the command.
     */
    final public function handle(): void
    {
        $clients = Peer::selectRaw('agent, COUNT(*) as user_count, SUM(peer_count) as peer_count')
            ->fromSub(
                Peer::query()
                    ->select(['agent', 'user_id', DB::raw('COUNT(*) as peer_count')])
                    ->groupBy('agent', 'user_id')
                    ->where('active', '=', true),
                'distinct_agent_user'
            )
            ->groupBy('agent')
            ->orderBy('agent')
            ->get()
            ->toArray();

        if (!empty($clients)) {
            cache()->put('stats:clients', $clients, Carbon::now()->addDay());
        }

        $this->comment('Automated Client Stats Completed.');
    }
}
