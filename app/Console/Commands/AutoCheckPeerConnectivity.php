<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Console\Commands;

use App\Models\Peer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoCheckPeerConnectivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:check_peer_connectivity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks connectivity of peers.';

    /**
     * Execute the console command.
     *
     * We need to calculate peer connectivity asynchronously from the http
     * request because of the 0.5-second delay that could stall one of the
     * workers until it times out, preventing the worker from handling any
     * other announces.
     */
    public function handle(): void
    {
        if (! config('announce.connectable_check')) {
            return;
        }

        Peer::query()
            ->select(['ip', 'port'])
            ->selectRaw('INET6_NTOA(ip) as ip')
            ->groupBy(['ip', 'port'])
            ->lazy()
            ->each(function (Peer $peer): void {
                $connection = @fsockopen(
                    // IPv6 Check
                    filter_var($peer->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? '['.$peer->ip.']' : $peer->ip,
                    $peer->port,
                    $_,
                    $_,
                    0.5
                );

                if ($connectable = \is_resource($connection)) {
                    fclose($connection);
                }

                Peer::query()
                    ->where('ip', '=', inet_pton($peer->ip))
                    ->where('port', '=', $peer->port)
                    ->where('connectable', '!=', $connectable)
                    ->update([
                        'connectable' => $connectable,
                        'updated_at'  => DB::raw('updated_at'),
                    ]);
            });

        $this->comment('Peer connectable status updates completed.');
    }
}
