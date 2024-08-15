<?php

declare(strict_types=1);

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
use App\Models\Warning;
use App\Notifications\UserPreWarning;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

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
    protected $description = 'Automatically Sends Pre Warning Notifications To Users';

    /**
     * Execute the console command.
     *
     * @throws Exception|Throwable If there is an error during the execution of the command.
     */
    final public function handle(): void
    {
        if (config('hitrun.enabled') === true) {
            $carbon = new Carbon();
            $prewarn = History::with(['user', 'torrent'])
                ->whereNull('prewarned_at')
                ->where('hitrun', '=', 0)
                ->where('immune', '=', 0)
                ->where('actual_downloaded', '>', 0)
                ->where('active', '=', 0)
                ->where('seedtime', '<=', config('hitrun.seedtime'))
                ->where('updated_at', '<', $carbon->copy()->subDays(config('hitrun.prewarn'))->toDateTimeString())
                ->get();

            $usersWithPreWarnings = [];

            foreach ($prewarn as $pre) {
                if (null === $pre->torrent) {
                    continue;
                }

                if (!$pre->user->group->is_immune && $pre->actual_downloaded > ($pre->torrent->size * (config('hitrun.buffer') / 100))) {
                    $exsist = Warning::withTrashed()
                        ->where('torrent', '=', $pre->torrent->id)
                        ->where('user_id', '=', $pre->user->id)
                        ->first();

                    if ($exsist === null) {
                        History::query()
                            ->where('torrent_id', '=', $pre->torrent_id)
                            ->where('user_id', '=', $pre->user_id)
                            ->update([
                                'prewarned_at' => now(),
                                'updated_at'   => DB::raw('updated_at'),
                            ]);

                        // Add user to usersWithWarnings array
                        $usersWithPreWarnings[$pre->user->id] = $pre->user;
                    }
                }
            }

            // Send a single notification for each user with warnings
            foreach ($usersWithPreWarnings as $user) {
                $user->notify(new UserPreWarning($user));
            }
        }

        $this->comment('Automated User Pre-Warning Command Complete');
    }
}
