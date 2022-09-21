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
 * @credits    Rhilip <https://github.com/Rhilip> Roardom <roardom@protonmail.com>
 */

namespace App\Jobs;

use App\Models\FreeleechToken;
use App\Models\History;
use App\Models\Peer;
use App\Models\PersonalFreeleech;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessAnnounce implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $queries, protected $user, protected $torrent)
    {
    }

    /**
     * Get the middleware the job should pass through.
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->user->id.':'.$this->torrent->id))->releaseAfter(30)];
    }

    /**
     * Execute the job.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle(): void
    {
        // Flag is tripped if new session is created but client reports up/down > 0
        $ghost = false;

        // Set Variables
        $realUploaded = $this->queries['uploaded'];
        $realDownloaded = $this->queries['downloaded'];
        $event = \strtolower($this->queries['event']);

        // Get The Current Peer
        $peer = Peer::query()
            ->where('torrent_id', '=', $this->torrent->id)
            ->where('peer_id', '=', $this->queries['peer_id'])
            ->where('user_id', '=', $this->user->id)
            ->first();

        // If no Peer record found then create one
        if ($peer === null) {
            if ($this->queries['uploaded'] > 0 || $this->queries['downloaded'] > 0) {
                $ghost = true;
                $event = 'started';
            }

            $peer = new Peer();
        }

        // Get history information
        $history = History::query()
            ->where('created_at', '>', $this->user->created_at)
            ->where('torrent_id', '=', $this->torrent->id)
            ->where('user_id', '=', $this->user->id)
            ->first();

        // If no History record found then create one
        if ($history === null) {
            $history = new History();
        }

        // Check Ghost Flag
        if ($ghost) {
            $uploaded = ($realUploaded >= $history->client_uploaded) ? ($realUploaded - $history->client_uploaded) : 0;
            $downloaded = ($realDownloaded >= $history->client_downloaded) ? ($realDownloaded - $history->client_downloaded) : 0;
        } else {
            $uploaded = ($realUploaded >= $peer->uploaded) ? ($realUploaded - $peer->uploaded) : 0;
            $downloaded = ($realDownloaded >= $peer->downloaded) ? ($realDownloaded - $peer->downloaded) : 0;
        }

        $oldUpdate = $peer->updated_at->timestamp ?? \now()->timestamp;

        // Modification of Upload and Download
        $personalFreeleech = PersonalFreeleech::query()
            ->where('user_id', '=', $this->user->id)
            ->first();

        $freeleechToken = FreeleechToken::query()
            ->where('user_id', '=', $this->user->id)
            ->where('torrent_id', '=', $this->torrent->id)
            ->first();

        if ($personalFreeleech ||
            $this->user->group->is_freeleech == 1 ||
            $freeleechToken ||
            \config('other.freeleech') == 1) {
            $modDownloaded = 0;
        } elseif ($this->torrent->free >= 1) {
            // FL value in DB are from 0% to 100%.
            // Divide it by 100 and multiply it with "downloaded" to get discount download.
            $fl_discount = $downloaded * $this->torrent->free / 100;
            $modDownloaded = $downloaded - $fl_discount;
        } else {
            $modDownloaded = $downloaded;
        }

        if ($this->torrent->doubleup == 1 ||
            $this->user->group->is_double_upload == 1 ||
            \config('other.doubleup') == 1) {
            $modUploaded = $uploaded * 2;
        } else {
            $modUploaded = $uploaded;
        }

        switch ($event) {
            case 'started':
                $peer->peer_id = $this->queries['peer_id'];
                $peer->md5_peer_id = \md5($this->queries['peer_id']);
                $peer->info_hash = $this->queries['info_hash'];
                $peer->ip = $this->queries['ip-address'];
                $peer->port = $this->queries['port'];
                $peer->agent = $this->queries['user-agent'];
                $peer->uploaded = $realUploaded;
                $peer->downloaded = $realDownloaded;
                $peer->seeder = (int) ($this->queries['left'] == 0);
                $peer->left = $this->queries['left'];
                $peer->torrent_id = $this->torrent->id;
                $peer->user_id = $this->user->id;
                $peer->updateConnectableStateIfNeeded();
                $peer->updated_at = \now();
                $peer->save();

                $history->user_id = $this->user->id;
                $history->torrent_id = $this->torrent->id;
                $history->info_hash = $this->queries['info_hash'];
                $history->agent = $this->queries['user-agent'];
                $history->active = 1;
                $history->seeder = (int) ($this->queries['left'] == 0);
                $history->immune = $this->user->group->is_immune == 1;
                $history->uploaded += 0;
                $history->actual_uploaded += 0;
                $history->client_uploaded = $realUploaded;
                $history->downloaded += 0;
                $history->actual_downloaded += 0;
                $history->client_downloaded = $realDownloaded;
                $history->save();
                break;

            case 'completed':
                $peer->peer_id = $this->queries['peer_id'];
                $peer->md5_peer_id = \md5($this->queries['peer_id']);
                $peer->info_hash = $this->queries['info_hash'];
                $peer->ip = $this->queries['ip-address'];
                $peer->port = $this->queries['port'];
                $peer->agent = $this->queries['user-agent'];
                $peer->uploaded = $realUploaded;
                $peer->downloaded = $realDownloaded;
                $peer->seeder = (int) ($this->queries['left'] == 0);
                $peer->left = $this->queries['left'];
                $peer->torrent_id = $this->torrent->id;
                $peer->user_id = $this->user->id;
                $peer->updateConnectableStateIfNeeded();
                $peer->updated_at = \now();
                $peer->save();

                $history->user_id = $this->user->id;
                $history->torrent_id = $this->torrent->id;
                $history->info_hash = $this->queries['info_hash'];
                $history->agent = $this->queries['user-agent'];
                $history->active = 1;
                $history->seeder = (int) ($this->queries['left'] == 0);
                $history->uploaded += $modUploaded;
                $history->actual_uploaded += $uploaded;
                $history->client_uploaded = $realUploaded;
                $history->downloaded += $modDownloaded;
                $history->actual_downloaded += $downloaded;
                $history->client_downloaded = $realDownloaded;
                $history->completed_at = \now();
                // Seedtime allocation
                if ($this->queries['left'] == 0) {
                    $newUpdate = $peer->updated_at->timestamp;
                    $diff = $newUpdate - $oldUpdate;
                    $history->seedtime += $diff;
                }
                $history->save();

                // User Update
                $this->user->uploaded += $modUploaded;
                $this->user->downloaded += $modDownloaded;
                $this->user->save();
                // End User Update

                // Torrent Completed Update
                $this->torrent->increment('times_completed');
                break;

            case 'stopped':
                $peer->touch();

                $history->user_id = $this->user->id;
                $history->torrent_id = $this->torrent->id;
                $history->info_hash = $this->queries['info_hash'];
                $history->agent = $this->queries['user-agent'];
                $history->active = 0;
                $history->seeder = (int) ($this->queries['left'] == 0);
                $history->uploaded += $modUploaded;
                $history->actual_uploaded += $uploaded;
                $history->client_uploaded = $realUploaded;
                $history->downloaded += $modDownloaded;
                $history->actual_downloaded += $downloaded;
                $history->client_downloaded = $realDownloaded;
                // Seedtime allocation
                if ($this->queries['left'] == 0) {
                    $newUpdate = $peer->updated_at->timestamp;
                    $diff = $newUpdate - $oldUpdate;
                    $history->seedtime += $diff;
                }
                $history->save();

                $peer->delete();

                // User Update
                $this->user->uploaded += $modUploaded;
                $this->user->downloaded += $modDownloaded;
                $this->user->save();
                // End User Update
                break;

            default:
                $peer->peer_id = $this->queries['peer_id'];
                $peer->md5_peer_id = \md5($this->queries['peer_id']);
                $peer->info_hash = $this->queries['info_hash'];
                $peer->ip = $this->queries['ip-address'];
                $peer->port = $this->queries['port'];
                $peer->agent = $this->queries['user-agent'];
                $peer->uploaded = $realUploaded;
                $peer->downloaded = $realDownloaded;
                $peer->seeder = (int) ($this->queries['left'] == 0);
                $peer->left = $this->queries['left'];
                $peer->torrent_id = $this->torrent->id;
                $peer->user_id = $this->user->id;
                $peer->updateConnectableStateIfNeeded();
                $peer->updated_at = \now();
                $peer->save();

                $history->user_id = $this->user->id;
                $history->torrent_id = $this->torrent->id;
                $history->info_hash = $this->queries['info_hash'];
                $history->agent = $this->queries['user-agent'];
                $history->active = 1;
                $history->seeder = (int) ($this->queries['left'] == 0);
                $history->uploaded += $modUploaded;
                $history->actual_uploaded += $uploaded;
                $history->client_uploaded = $realUploaded;
                $history->downloaded += $modDownloaded;
                $history->actual_downloaded += $downloaded;
                $history->client_downloaded = $realDownloaded;
                // Seedtime allocation
                if ($this->queries['left'] == 0) {
                    $newUpdate = $peer->updated_at->timestamp;
                    $diff = $newUpdate - $oldUpdate;
                    $history->seedtime += $diff;
                }

                $history->save();

                // User Update
                $this->user->uploaded += $modUploaded;
                $this->user->downloaded += $modDownloaded;
                $this->user->save();
                // End User Update
        }

        $peerCount = DB::table('peers')
            ->where('torrent_id', '=', $this->torrent->id)
            ->selectRaw('count(case when peers.left > 0 then 1 end) as leechers')
            ->selectRaw('count(case when peers.left = 0 then 1 end) as seeders')
            ->first();
        $this->torrent->seeders = $peerCount->seeders;
        $this->torrent->leechers = $peerCount->leechers;
        $this->torrent->save();
    }
}
