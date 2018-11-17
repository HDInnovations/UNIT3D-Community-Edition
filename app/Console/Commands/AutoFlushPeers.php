<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\History;
use App\Peer;

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
        foreach (Peer::select(['id', 'info_hash', 'user_id', 'updated_at'])->get() as $peer) {
            if ((time() - strtotime($peer->updated_at)) > (60 * 60 * 2)) {
                $history = History::where("info_hash", $peer->info_hash)->where("user_id", $peer->user_id)->first();
                if ($history) {
                    $history->active = false;
                    $history->save();
                }
                $peer->delete();
            }
        }
    }
}
