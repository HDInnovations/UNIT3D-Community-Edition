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
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        $peers = Peer::where('seeder', '=', 1)->get();

        $user_id = [];
        $clients = [];
        $clients_tmp = [];

        // Goal is to calculate the number of users and not the peer count
        foreach ($peers as $peer) {
            if (! \in_array($peer->user_id, $user_id)) {
                $user_id[] = $peer->user_id;
                $clients_tmp[] = $peer->agent;
                $clients[(string) $peer->agent] = 1;
            } elseif (! \in_array($peer->agent, $clients_tmp) && \in_array($peer->user_id, $user_id)) {
                $clients_tmp[] = $peer->agent;
                $clients[(string) $peer->agent] = 1;
            } else {
                $clients[(string) $peer->agent]++;
            }
        }

        if (! empty($clients)) {
            // Sort Clients by Array Key Alphabetically
            \ksort($clients);

            \cache()->put('stats:clients', $clients, Carbon::now()->addMinutes(1440));
        }

        $this->comment('Automated Client Stats Completed.');
    }
}
