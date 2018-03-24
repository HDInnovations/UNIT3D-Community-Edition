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
use App\History;
use App\PrivateMessage;
use App\User;
use App\Warning;
use Carbon\Carbon;

class autoPreWarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoPreWarning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Sends Pre Warning PM To Users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('hitrun.enabled') == true) {
            $current = new Carbon();
            $prewarn = History::with(['user', 'torrent'])
                            ->where('actual_downloaded', '>', 0)
                            ->where('active', 0)
                            ->where('seedtime', '<=', config('hitrun.seedtime'))
                            ->where('updated_at', '<', $current->copy()->subDays(config('hitrun.prewarn'))->toDateTimeString())
                            ->get();

            foreach ($prewarn as $pre) {
                if (!$pre->user->group->is_immune) {
                    if ($pre->actual_downloaded > ($pre->torrent->size * (config('hitrun.buffer') / 100))) {
                        $exsist = Warning::where('torrent', $pre->torrent->id)->where('user_id', $pre->user->id)->first();

                        // Send Pre Warning PM If Actual Warning Doesnt Already Exsist
                        if (!$exsist) {
                            $timeleft = config('hitrun.grace') - config('hitrun.prewarn');

                            // Send Prewarning PM To The Offender
                            PrivateMessage::create(['sender_id' => "1", 'reciever_id' => $pre->user->id, 'subject' => "Hit and Run Warning Incoming", 'message' => "You have received a automated [b]PRE-WARNING PM[/b] from the system because [b]you have been disconnected for " . config('hitrun.prewarn') . " days on Torrent " . $pre->torrent->name . "
                        If you fail to seed it within " . $timeleft . " day(s) you will recieve a automated WARNING which will last " . config('hitrun.expire') ." days![/b]
                        [color=red][b] THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]"]);
                        }

                        unset($exist);
                    }
                }
            }
        }
    }
}
