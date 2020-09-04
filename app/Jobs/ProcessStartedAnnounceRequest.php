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

namespace App\Jobs;

use App\Models\History;
use App\Models\Peer;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessStartedAnnounceRequest implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $queries;

    protected $user;

    protected $torrent;

    /**
     * ProcessAnnounceRequest constructor.
     *
     * @param                     $queries
     * @param \App\Models\User    $user
     * @param \App\Models\Torrent $torrent
     */
    public function __construct($queries, User $user, Torrent $torrent)
    {
        $this->queries = $queries;
        $this->user = $user;
        $this->torrent = $torrent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get The Current Peer
        $peer = Peer::where('torrent_id', '=', $this->torrent->id)
            ->where('peer_id', $this->queries['peer_id'])
            ->where('user_id', '=', $this->user->id)
            ->first();

        // Creates a new peer if not existing
        if ($peer === null) {
            if ($this->queries['uploaded'] > 0 || $this->queries['downloaded'] > 0) {
                $this->queries['event'] = 'started';
            }
            $peer = new Peer();
        }

        // Get history information
        $history = History::where('info_hash', '=', $this->queries['info_hash'])->where('user_id', '=', $this->user->id)->first();

        // If no History record found then create one
        if ($history === null) {
            $history = new History();
            $history->user_id = $this->user->id;
            $history->info_hash = $this->queries['info_hash'];
        }

        $real_uploaded = $this->queries['uploaded'];
        $real_downloaded = $this->queries['downloaded'];

        // Peer Update
        $peer->peer_id = $this->queries['peer_id'];
        $peer->md5_peer_id = \md5($this->queries['peer_id']);
        $peer->info_hash = $this->queries['info_hash'];
        $peer->ip = $this->queries['ip-address'];
        $peer->port = $this->queries['port'];
        $peer->agent = $this->queries['user-agent'];
        $peer->uploaded = $real_uploaded;
        $peer->downloaded = $real_downloaded;
        $peer->seeder = $this->queries['left'] == 0;
        $peer->left = $this->queries['left'];
        $peer->torrent_id = $this->torrent->id;
        $peer->user_id = $this->user->id;
        $peer->save();
        // End Peer Update

        // History Update
        $history->agent = $this->queries['user-agent'];
        $history->active = 1;
        $history->seeder = $this->queries['left'] == 0;
        $history->immune = $this->user->group->is_immune == 1;
        $history->uploaded += 0;
        $history->actual_uploaded += 0;
        $history->client_uploaded = $real_uploaded;
        $history->downloaded += 0;
        $history->actual_downloaded += 0;
        $history->client_downloaded = $real_downloaded;
        $history->save();
        // End History Update

        // Sync Seeders / Leechers Count
        $this->torrent->seeders = Peer::where('torrent_id', '=', $this->torrent->id)->where('left', '=', '0')->count();
        $this->torrent->leechers = Peer::where('torrent_id', '=', $this->torrent->id)->where('left', '>', '0')->count();
        $this->torrent->save();
    }
}
