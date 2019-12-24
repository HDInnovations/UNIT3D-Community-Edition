<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Console\Commands;

use App\Models\History;
use App\Models\PrivateMessage;
use App\Models\Warning;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;

final class AutoPreWarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected string $signature = 'auto:prewarning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'Automatically Sends Pre Warning PM To Users';
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;

    public function __construct(Repository $configRepository)
    {
        $this->configRepository = $configRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        if ($this->configRepository->get('hitrun.enabled') == true) {
            $current = new Carbon();
            $prewarn = History::with(['user', 'torrent'])
                ->where('prewarn', '=', 0)
                ->where('hitrun', '=', 0)
                ->where('immune', '=', 0)
                ->where('actual_downloaded', '>', 0)
                ->where('active', '=', 0)
                ->where('seedtime', '<=', $this->configRepository->get('hitrun.seedtime'))
                ->where('updated_at', '<', $current->copy()->subDays($this->configRepository->get('hitrun.prewarn'))->toDateTimeString())
                ->get();

            foreach ($prewarn as $pre) {
                if (! $pre->user->group->is_immune) {
                    if ($pre->actual_downloaded > ($pre->torrent->size * ($this->configRepository->get('hitrun.buffer') / 100))) {
                        $exsist = Warning::withTrashed()
                            ->where('torrent', '=', $pre->torrent->id)
                            ->where('user_id', '=', $pre->user->id)
                            ->first();

                        // Send Pre Warning PM If Actual Warning Doesnt Already Exsist
                        if ($exsist === null) {
                            $timeleft = $this->configRepository->get('hitrun.grace') - $this->configRepository->get('hitrun.prewarn');

                            // Send Private Message
                            $pm = new PrivateMessage();
                            $pm->sender_id = 1;
                            $pm->receiver_id = $pre->user->id;
                            $pm->subject = 'Hit and Run Warning Incoming';
                            $pm->message = 'You have received a automated [b]PRE-WARNING PM[/b] from the system because [b]you have been disconnected for '.$this->configRepository->get('hitrun.prewarn').sprintf(' days on Torrent %s
                                            and have not yet met the required seedtime rules set by ', $pre->torrent->name).$this->configRepository->get('other.title').sprintf('. If you fail to seed it within %s day(s) you will receive a automated WARNING which will last ', $timeleft).$this->configRepository->get('hitrun.expire').' days![/b]
                                            [color=red][b] THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
                            $pm->save();

                            // Set History Prewarn
                            $pre->prewarn = 1;
                            $pre->save();
                        }
                    }
                }
            }
        }
    }
}
