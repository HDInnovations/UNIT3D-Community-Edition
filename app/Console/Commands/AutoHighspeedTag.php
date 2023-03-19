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
use App\Models\Seedbox;
use App\Models\Torrent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * @see \Tests\Unit\Console\Commands\AutoHighspeedTagTest
 */
class AutoHighspeedTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:highspeed_tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Torrents Highspeed Tag Based On Registered Seedboxes.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $seedboxIps = Seedbox::all()->pluck('ip')->filter(fn ($ip) => filter_var($ip, FILTER_VALIDATE_IP));

        Torrent::withAnyStatus()
            ->leftJoinSub(
                Peer::distinct()
                    ->select('torrent_id')
                    ->whereRaw("INET6_NTOA(ip) IN ('".$seedboxIps->implode("','")."')"),
                'highspeed_torrents',
                fn ($join) => $join->on('torrents.id', '=', 'highspeed_torrents.torrent_id')
            )
            ->update([
                'highspeed' => DB::raw('CASE WHEN highspeed_torrents.torrent_id IS NOT NULL THEN 1 ELSE 0 END'),
            ]);

        $this->comment('Automated High Speed Torrents Command Complete');
    }
}
