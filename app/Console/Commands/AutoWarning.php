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
use App\Models\PrivateMessage;
use App\Models\TorrentRequest;
use App\Models\Warning;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * @see \Tests\Unit\Console\Commands\AutoWarningTest
 */
class AutoWarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:warning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Post Warnings To Users Accounts and Warnings Table';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function handle()
    {
        if (\config('hitrun.enabled') == true) {
            $carbon = new Carbon();
            $userRequests = TorrentRequest::whereNotNull('filled_hash')->get()->toArray();
            $hitrun = History::with(['user', 'torrent'])
                ->where('actual_downloaded', '>', 0)
                ->where('prewarn', '=', 1)
                ->where('hitrun', '=', 0)
                ->where('immune', '=', 0)
                ->where('active', '=', 0)
                ->where('seedtime', '<', \config('hitrun.seedtime'))
                ->where('updated_at', '<', $carbon->copy()->subDays(\config('hitrun.grace'))->toDateTimeString())
                ->get();
            $hitrunRequests = History::with(['user', 'torrent'])
                ->where('actual_downloaded', '>', 0)
                ->where('prewarn', '=', 1)
                ->where('hitrun', '=', 0)
                ->where('immune', '=', 0)
                ->where('active', '=', 0)
                ->where('seedtime', '<', \config('hitrun.seedtime_requests'))
                ->where('seedtime', '>=', \config('hitrun.seedtime'))
                ->where('updated_at', '<', $carbon->copy()->subDays(\config('hitrun.grace'))->toDateTimeString())
                ->get();
            $merge = $hitrunRequests->merge($hitrun);

            foreach ($merge as $hr) {
                if (! $hr->user->group->is_immune && $hr->actual_downloaded > ($hr->torrent->size * (\config('hitrun.buffer') / 100))) {
                    $exsist = Warning::withTrashed()
                        ->where('torrent', '=', $hr->torrent->id)
                        ->where('user_id', '=', $hr->user->id)
                        ->first();
                    // Insert Warning Into Warnings Table if doesnt already exsist
                    if ($exsist === null) {
                        $warning = new Warning();
                        $warning->user_id = $hr->user->id;
                        $warning->warned_by = '1';
                        $warning->torrent = $hr->torrent->id;
                        $warning->reason = \sprintf('Hit and Run Warning For Torrent %s', $hr->torrent->name);
                        $warning->expires_on = $carbon->copy()->addDays(\config('hitrun.expire'));
                        $warning->active = '1';
                        $warning->save();

                        // Add +1 To Users Warnings Count In Users Table
                        $hr->hitrun = 1;
                        $hr->user->hitandruns++;
                        $hr->user->save();

                        // When seedtime requirements for a requested torrent
                        foreach ($userRequests as $userRequest) {
                            if (in_array($hr->torrent->info_hash, $userRequest) && $hr->torrent->seedtime < config('hitrun.seedtime_requests')) {
                                // Send Private Message
                                $pm = new PrivateMessage();
                                $pm->sender_id = 1;
                                $pm->receiver_id = $hr->user->id;
                                $pm->subject = \sprintf('Hit and Run Warning Incoming');
                                $pm->message = \sprintf('You have received an automated [b]WARNING[/b] from the system, because you failed to follow the Hit and Run rules in relation to the Torrent:
                                    [u][url=/torrents/%s]%s[/url][/u].
                                    
                                    You have requested this torrent, this means it is subject to the extended seedtime 
                                    requirements defined in our [u][url=', $hr->torrent->id, $hr->torrent->name)
                                    .\config('other.request-rules_url')
                                    .\sprintf(']Request Rules[/url][/u].
                                    
                                    [color=red][b] THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]'
                                    );
                                $pm->save();
                            }
                        }

                        // When seedtime requirements for default torrent
                        if (!$prewarnRequests->contains('info_hash', $hr->torrent->info_hash)) {
                            // Send Private Message
                            $pm = new PrivateMessage();
                            $pm->sender_id = 1;
                            $pm->receiver_id = $hr->user->id;
                            $pm->subject = \sprintf('Hit and Run Warning Received');
                            $pm->message = \sprintf('You have received an automated [b]WARNING[/b] from the system, because you failed to follow the Hit and Run rules in relation to Torrent:
                                [u][url=/torrents/%s]%s[/url][/u].

                                [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]',
                                $hr->torrent->id, $hr->torrent->name);
                            $pm->save();
                        }

                        $hr->save();
                    }
                }
            }
        }

        $this->comment('Automated User Warning Command Complete');
    }
}
