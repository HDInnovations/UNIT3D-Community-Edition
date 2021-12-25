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
use App\Models\Warning;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * @see \Tests\Unit\Console\Commands\AutoPreWarningTest
 */
class AutoPreWarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:prewarning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Sends Pre Warning PM To Users';

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
            $prewarn = History::with(['user', 'torrent'])
                ->where('prewarn', '=', 0)
                ->where('hitrun', '=', 0)
                ->where('immune', '=', 0)
                ->where('actual_downloaded', '>', 0)
                ->where('active', '=', 0)
                ->where('seedtime', '<=', \config('hitrun.seedtime'))
                ->where('updated_at', '<', $carbon->copy()->subDays(\config('hitrun.prewarn'))->toDateTimeString())
                ->get();
            $prewarnRequests = History::with(['user', 'torrent'])
                ->where('prewarn', '=', 0)
                ->where('hitrun', '=', 0)
                ->where('immune', '=', 0)
                ->where('actual_downloaded', '>', 0)
                ->where('active', '=', 0)
                ->where('seedtime', '<=', \config('hitrun.seedtime_requests'))
                ->where('seedtime', '>', \config('hitrun.seedtime'))
                ->where('updated_at', '<', $carbon->copy()->subDays(\config('hitrun.prewarn'))->toDateTimeString())
                ->get();
            $merge = $prewarnRequests->merge($prewarn);

            foreach ($merge as $pre) {
                // Skip Prewarning if Torrent is NULL
                // e.g. Torrent has been Rejected by a Moderator after it has been downloaded and not deleted
                if (is_null($pre->torrent)) {
                    continue;
                }

                if (! $pre->user->group->is_immune && $pre->actual_downloaded > ($pre->torrent->size * (\config('hitrun.buffer') / 100))) {
                    $exsist = Warning::withTrashed()
                        ->where('torrent', '=', $pre->torrent->id)
                        ->where('user_id', '=', $pre->user->id)
                        ->first();
                    // Send Pre Warning PM If Actual Warning Doesnt Already Exsist
                    if ($exsist === null) {
                        $timeleft = \config('hitrun.grace') - \config('hitrun.prewarn');

                        // Send Private Message
                        if ($prewarnRequests->contains('info_hash', $pre->torrent->info_hash)) {
                            // When seedtime requirements for requested torrent
                            $pm = new PrivateMessage();
                            $pm->sender_id = 1;
                            $pm->receiver_id = $pre->user->id;
                            $pm->subject = \sprintf('Hit and Run Warning Incoming');
                            $pm->message = \sprintf('You have received an automated [b]PRE-WARNING PM[/b] from the system, because [b]you have been disconnected[/b] for ').\config('hitrun.prewarn').\sprintf(' days on Torrent 
                                [u][url=/torrents/%s]%s[/url][/u].

                                If you fail to seed it within %s day(s) you will receive an automated WARNING!

                                You have requested this torrent, this means it is subject to the extended seedtime 
                                requirements defined in our [u][url=', $pre->torrent->id, $pre->torrent->name, $timeleft)
                                .\config('other.request-rules_url')
                                .\sprintf(']Request Rules[/url][/u].
                                
                                [color=red][b] THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]'
                                );
                            $pm->save();
                        } else {
                            // When seedtime requirements for default torrent
                            $pm = new PrivateMessage();
                            $pm->sender_id = 1;
                            $pm->receiver_id = $pre->user->id;
                            $pm->subject = 'Hit and Run Warning Incoming';
                            $pm->message = \sprintf('You have received an automated [b]PRE-WARNING PM[/b] from the system, because [b]you have been disconnected[/b] for
                                ').\config('hitrun.prewarn').\sprintf(' days on Torrent [u][url=/torrents/%s]%s[/url][/u].

                                If you fail to seed it within %s day(s) you will receive an automated WARNING!
                                
                                [color=red][b] THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]',
                                $pre->torrent->id, $pre->torrent->name, $timeleft);
                            $pm->save();
                        }

                        // Set History Prewarn
                        $pre->prewarn = 1;
                        $pre->save();
                    }
                }
            }
        }

        $this->comment('Automated User Pre-Warning Command Complete');
    }
}
