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

namespace App\Console\Commands;

use App\Models\Peer;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

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
     */
    public function handle(): void
    {
        $clients = Peer::selectRaw('agent, count(*) as count')
            ->fromSub(
                Peer::query()
                    ->select(['agent', 'user_id'])
                    ->groupBy('agent', 'user_id')
                    ->where('active', '=', true),
                'distinct_agent_user'
            )
            ->groupBy('agent')
            ->orderBy('agent')
            ->pluck('count', 'agent')
            ->toArray();

        if (!empty($clients)) {
            cache()->put('stats:clients', $clients, Carbon::now()->addDay());
        }

        $this->comment('Automated Client Stats Completed.');
    }
}
