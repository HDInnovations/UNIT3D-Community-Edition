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

use App\Enums\UserGroup;
use App\Models\Group;
use App\Models\User;
use App\Services\Unit3dAnnounce;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class AutoGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:group';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Change A Users Group Class If Requirements Met';

    /**
     * Execute the console command.
     *
     * @throws Exception|Throwable If there is an error during the execution of the command.
     */
    final public function handle(): void
    {
        $now = now();
        $current = Carbon::now();

        $groups = Group::query()
            ->where('autogroup', '=', 1)
            ->orderBy('position')
            ->get();

        $users = User::query()
            ->whereIntegerInRaw('group_id', $groups->pluck('id'))
            ->get();

        foreach ($users as $user) {
            // memoize when necessary
            $seedtime = null;
            $seedsize = null;
            $uploads = null;

            foreach ($groups as $group) {
                $seedtime ??= DB::table('history')
                    ->where('user_id', '=', $user->id)
                    ->avg('seedtime') ?? 0;

                $seedsize ??= $user->seedingTorrents()->sum('size');

                $uploads ??= $user->torrents()->count();

                if (
                    //short circuit when the values are 0 or null
                    (!$group->min_uploaded || $group->min_uploaded <= $user->uploaded)
                    && (!$group->min_ratio || $group->min_ratio <= $user->ratio)
                    && (!$group->min_age || $user->created_at->addSeconds($group->min_age)->isBefore($current))
                    && (!$group->min_avg_seedtime || $group->min_avg_seedtime <= ($seedtime))
                    && (!$group->min_seedsize || $group->min_seedsize <= ($seedsize))
                    && (!$group->min_uploads || $group->min_uploads <= ($uploads))
                ) {
                    $user->group_id = $group->id;

                    // Leech ratio dropped below sites minimum
                    if ($user->group_id === UserGroup::LEECH->value) {
                        $user->can_request = false;
                        $user->can_invite = false;
                        $user->can_download = false;
                    } else {
                        $user->can_request = true;
                        $user->can_invite = true;
                        $user->can_download = true;
                    }
                    $user->save();

                    if ($user->wasChanged()) {
                        cache()->forget('user:'.$user->passkey);

                        Unit3dAnnounce::addUser($user);
                    }
                }
            }
        }

        $elapsed = now()->diffInSeconds($now);
        $this->comment('Automated User Group Command Complete ('.$elapsed.')');
    }
}
