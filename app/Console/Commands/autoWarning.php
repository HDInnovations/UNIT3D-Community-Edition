<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\History;
use App\PrivateMessage;
use App\Warning;
use Carbon\Carbon;

class autoWarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoWarning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Post Warnings To Users Accounts and Warnings Table';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('hitrun.enabled') == true) {
            $current = new Carbon();
            $hitrun = History::with(['user', 'torrent'])
                            ->where('actual_downloaded', '>', 0)
                            ->where('active', 0)
                            ->where('seedtime', '<=', config('hitrun.seedtime'))
                            ->where('updated_at', '<', $current->copy()->subDays(config('hitrun.grace'))->toDateTimeString())
                            ->get();

            foreach ($hitrun as $hr) {
                if (!$hr->user->group->is_immune) {
                    if ($hr->actual_downloaded > ($hr->torrent->size * (config('hitrun.buffer') / 100))) {
                        $exsist = Warning::where('torrent', $hr->torrent->id)->where('user_id', $hr->user->id)->first();

                        // Insert Warning Into Warnings Table if doesnt already exsist
                        if (!$exsist) {
                            $warning = new Warning();
                            $warning->user_id = $hr->user->id;
                            $warning->warned_by = "1";
                            $warning->torrent = $hr->torrent->id;
                            $warning->reason = "Hit and Run Warning For Torrent {$hr->torrent->name}";
                            $warning->expires_on = $current->copy()->addDays(config('hitrun.expire'));
                            $warning->active = "1";
                            $warning->save();

                            // Add +1 To Users Warnings Count In Users Table
                            $hr->user->hitandruns++;
                            $hr->user->save();

                            // Send PM To The Offender
                            PrivateMessage::create(['sender_id' => "1", 'reciever_id' => $hr->user->id, 'subject' => "Hit and Run Warning Recieved", 'message' => "You have received a automated [b]WARNING[/b] from the system because [b]you failed to follow the Hit and Run rules in relation to Torrent " . $hr->torrent->name . "[/b]
                        [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]"]);
                        }

                        unset($exist);
                    }
                }
            }
        }
    }
}
