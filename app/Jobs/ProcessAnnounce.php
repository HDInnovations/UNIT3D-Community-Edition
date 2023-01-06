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

use App\Models\History;
use App\Models\Peer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class ProcessAnnounce implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $queries, protected $user, protected $torrent, protected $group)
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
        $peer = $this->torrent->peers
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
        $personalFreeleech = \cache()->rememberForever(
            'personal_freeleech:'.$this->user->id,
            fn () => $this->user->personalFreeleeches()->exists()
        );

        $freeleechToken = \cache()->rememberForever(
            'freeleech_token:'.$this->user->id.':'.$this->torrent->id,
            fn () => $this->user->freeleechTokens()->where('torrent_id', '=', $this->torrent->id)->exists()
        );

        if ($personalFreeleech ||
            $this->group->is_freeleech == 1 ||
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
            $this->group->is_double_upload == 1 ||
            \config('other.doubleup') == 1) {
            $modUploaded = $uploaded * 2;
        } else {
            $modUploaded = $uploaded;
        }

        // Common Parts Extracted From Switch
        $peer->peer_id = $this->queries['peer_id'];
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
        $history->agent = $this->queries['user-agent'];
        $history->seeder = (int) ($this->queries['left'] == 0);
        $history->client_uploaded = $realUploaded;
        $history->client_downloaded = $realDownloaded;

        switch ($event) {
            case 'started':

                $history->active = 1;
                // Allow downgrading from `immune`, but never upgrade to it
                $history->immune = (int) ($history->immune === null ? $this->group->is_immune : (bool) $history->immune && (bool) $this->group->is_immune);
                $history->save();
                break;

            case 'completed':

                $history->active = 1;
                $history->uploaded += $modUploaded;
                $history->actual_uploaded += $uploaded;
                $history->downloaded += $modDownloaded;
                $history->actual_downloaded += $downloaded;
                $history->completed_at = \now();

                // Seedtime allocation
                if ($this->queries['left'] == 0) {
                    $newUpdate = $peer->updated_at->timestamp;
                    $diff = $newUpdate - $oldUpdate;
                    $history->seedtime += $diff;
                }
                $history->save();

                // User Update
                if ($modUploaded > 0 || $modDownloaded > 0) {
                    $this->user->update([
                        'uploaded'   => DB::raw('uploaded + '. (int) $modUploaded),
                        'downloaded' => DB::raw('downloaded + '. (int) $modDownloaded),
                    ]);
                }
                // End User Update

                // Torrent Completed Update
                $this->torrent->increment('times_completed');
                break;

            case 'stopped':

                $history->active = 0;
                $history->uploaded += $modUploaded;
                $history->actual_uploaded += $uploaded;
                $history->downloaded += $modDownloaded;
                $history->actual_downloaded += $downloaded;

                // Seedtime allocation
                if ($this->queries['left'] == 0) {
                    $newUpdate = $peer->updated_at->timestamp;
                    $diff = $newUpdate - $oldUpdate;
                    $history->seedtime += $diff;
                }
                $history->save();

                $peer->delete();

                // User Update
                if ($modUploaded > 0 || $modDownloaded > 0) {
                    $this->user->update([
                        'uploaded'   => DB::raw('uploaded + '. (int) $modUploaded),
                        'downloaded' => DB::raw('downloaded + '. (int) $modDownloaded),
                    ]);
                }
                // End User Update
                break;

            default:

                $history->active = 1;
                $history->uploaded += $modUploaded;
                $history->actual_uploaded += $uploaded;
                $history->downloaded += $modDownloaded;
                $history->actual_downloaded += $downloaded;

                // Seedtime allocation
                if ($this->queries['left'] == 0) {
                    $newUpdate = $peer->updated_at->timestamp;
                    $diff = $newUpdate - $oldUpdate;
                    $history->seedtime += $diff;
                }

                $history->save();

                // User Update
                if ($modUploaded > 0 || $modDownloaded > 0) {
                    $this->user->update([
                        'uploaded'   => DB::raw('uploaded + '. (int) $modUploaded),
                        'downloaded' => DB::raw('downloaded + '. (int) $modDownloaded),
                    ]);
                }
                // End User Update
        }

        $otherSeeders = $this
            ->torrent
            ->peers
            ->where('left', '=', 0)
            ->where('peer_id', '<>', $this->queries['peer_id'])
            ->count();
        $otherLeechers = $this
            ->torrent
            ->peers
            ->where('left', '>', 0)
            ->where('peer_id', '<>', $this->queries['peer_id'])
            ->count();

        $this->torrent->seeders = $otherSeeders + (int) ($this->queries['left'] == 0);
        $this->torrent->leechers = $otherLeechers + (int) ($this->queries['left'] > 0);

        $this->torrent->save();
    }
}
