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

use App\Helpers\ByteUnits;
use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use App\Services\Unit3dAnnounce;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @see \Tests\Unit\Console\Commands\AutoGroupTest
 */
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
     */
    public function handle(ByteUnits $byteUnits): void
    {
        $now = now();
        // Temp Hard Coding of Immune Groups (Config Files To Come)
        $current = Carbon::now();
        $groups = Group::query()
            ->where('autogroup', '=', 1)
            ->orderBy('position')
            ->get();

        $roles = Role::where('auto', '=', true)->get();

        $users = User::query()
            ->whereIntegerInRaw('group_id', $groups->pluck('id'))
            ->get();

        foreach ($users as $user) {
            // memoize when necessary
            $seedsize = null;
            $seedtime = null;

            foreach ($groups as $group) {
                if (
                    //short circuit when the values are 0 or null
                    ($group->min_uploaded ? $group->min_uploaded <= $user->uploaded : true)
                    && ($group->min_ratio ? $group->min_ratio <= $user->ratio : true)
                    && ($group->min_age ? $user->created_at->addRealSeconds($group->min_age)->isBefore($current) : true)
                    && ($group->min_avg_seedtime ? $group->min_avg_seedtime <= ($seedtime ??= DB::table('history')->where('user_id', '=', $user->id)->avg('seedtime') ?? 0) : true)
                    && ($group->min_seedsize ? $group->min_seedsize <= ($seedsize ??= $user->seedingTorrents()->sum('size')) : true)
                ) {
                    $user->group_id = $group->id;
                } else {
                    break;
                }
            }

            $warningsBalance = null;

            foreach ($roles as $role) {
                if (
                    ($role->warnings_active_min === null || $role->warnings_active_min <= ($warningsBalance ??= $user->userwarning()->where('active', '=', true)->count()))
                    && ($role->warnings_active_max === null || $role->warnings_active_max >= ($warningsBalance ??= $user->userwarning()->where('active', '=', true)->count()))
                ) {
                    $user->autoRoles()->attach($role);
                }
            }

            $user->save();

            if ($user->wasChanged()) {
                cache()->forget('user:'.$user->passkey);

                Unit3dAnnounce::addUser($user);
            }
        }

        $elapsed = now()->floatDiffInSeconds($now);
        $this->comment('Automated User Group Command Complete ('.$elapsed.')');
    }
}
