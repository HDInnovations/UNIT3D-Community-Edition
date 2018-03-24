<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Torrent;
use App\Peer;
use App\Client;

class autoSeedbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoSeedbox';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Torrents Highspeed Tag based on registered seedboxes.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('torrents')->update(['highspeed' => 0]);

        $seedboxips = Client::select('ip')->get()->toArray();

        if (is_array($seedboxips) && count($seedboxips) > 0) {
            $torid = Peer::select('torrent_id')->whereIn('ip', $seedboxips)->where('seeder', 1)->get()->toArray();

            foreach ($torid as $id) {
                $torrent = Torrent::where('id', $id)->first();
                $torrent->highspeed = 1;
                $torrent->save();

                unset($torrent);
            }
        }
    }
}
