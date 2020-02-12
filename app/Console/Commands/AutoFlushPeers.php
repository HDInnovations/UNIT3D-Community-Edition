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

use App\Models\History;
use App\Models\Peer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoFlushPeers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:flush_peers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flushes Ghost Peers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $current = new Carbon();
        $peers = Peer::select(['id', 'info_hash', 'user_id', 'updated_at'])->where('updated_at', '<', $current->copy()->subHours(2)->toDateTimeString())->get();

        foreach ($peers as $peer) {
            $history = History::where('info_hash', '=', $peer->info_hash)->where('user_id', '=', $peer->user_id)->first();
            if ($history) {
                $history->active = false;
                $history->save();
            }
            $peer->delete();
        }
    }
}
