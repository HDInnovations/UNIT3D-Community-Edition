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

use App\Exceptions\TrackerException;
use App\Models\FreeleechToken;
use App\Models\Group;
use App\Models\History;
use App\Models\Peer;
use App\Models\PersonalFreeleech;
use App\Models\Torrent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessStoppedAnnounceRequest implements ShouldQueue
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
     * @throws \Exception
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

        // Flag is tripped if new session is created but client reports up/down > 0
        $ghost = false;
        if ($peer === null && strtolower($this->queries['event']) === 'completed') {
            throw new TrackerException(151);
        }

        // Creates a new peer if not existing
        if ($peer === null) {
            if ($this->queries['uploaded'] > 0 || $this->queries['downloaded'] > 0) {
                $ghost = true;
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

        if ($ghost) {
            $uploaded = ($real_uploaded >= $history->client_uploaded) ? ($real_uploaded - $history->client_uploaded) : 0;
            $downloaded = ($real_downloaded >= $history->client_downloaded) ? ($real_downloaded - $history->client_downloaded) : 0;
        } else {
            $uploaded = ($real_uploaded >= $peer->uploaded) ? ($real_uploaded - $peer->uploaded) : 0;
            $downloaded = ($real_downloaded >= $peer->downloaded) ? ($real_downloaded - $peer->downloaded) : 0;
        }

        $old_update = $peer->updated_at ? $peer->updated_at->timestamp : Carbon::now()->timestamp;

        // Modification of Upload and Download
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $this->user->id)->first();
        $freeleech_token = FreeleechToken::where('user_id', '=', $this->user->id)->where('torrent_id', '=', $this->torrent->id)->first();
        $group = Group::whereId($this->user->group_id)->first();

        if (config('other.freeleech') == 1 || $this->torrent->free == 1 || $personal_freeleech || $group->is_freeleech == 1 || $freeleech_token) {
            $mod_downloaded = 0;
        } else {
            $mod_downloaded = $downloaded;
        }

        if (config('other.doubleup') == 1 || $this->torrent->doubleup == 1 || $group->is_double_upload == 1) {
            $mod_uploaded = $uploaded * 2;
        } else {
            $mod_uploaded = $uploaded;
        }

        // Peer Update
        $peer->peer_id = $this->queries['peer_id'];
        $peer->md5_peer_id = md5($this->queries['peer_id']);
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
        $history->active = 0;
        $history->seeder = $this->queries['left'] == 0;
        $history->uploaded += $mod_uploaded;
        $history->actual_uploaded += $uploaded;
        $history->client_uploaded = $real_uploaded;
        $history->downloaded += $mod_downloaded;
        $history->actual_downloaded += $downloaded;
        $history->client_downloaded = $real_downloaded;
        // Seedtime allocation
        if ($this->queries['left'] == 0) {
            $new_update = $peer->updated_at->timestamp;
            $diff = $new_update - $old_update;
            $history->seedtime += $diff;
        }
        $history->save();
        // End History Update

        // Peer Delete (Now that history is updated)
        $peer->delete();
        // End Peer Delete

        // User Update
        $this->user->uploaded += $mod_uploaded;
        $this->user->downloaded += $mod_downloaded;
        $this->user->save();
        // End User Update

        // Sync Seeders / Leechers Count
        $this->torrent->seeders = Peer::where('torrent_id', '=', $this->torrent->id)->where('left', '=', '0')->count();
        $this->torrent->leechers = Peer::where('torrent_id', '=', $this->torrent->id)->where('left', '>', '0')->count();
        $this->torrent->save();
    }
}
