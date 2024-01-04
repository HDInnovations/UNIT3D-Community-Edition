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
use App\Helpers\ByteUnits;
use App\Models\Group;
use App\Models\History;
use App\Models\User;
use App\Services\Unit3dAnnounce;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

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
        // Temp Hard Coding of Immune Groups (Config Files To Come)
        $current = Carbon::now();
        $groups = Group::where('autogroup', '=', 1)->pluck('id');

        foreach (User::whereIntegerInRaw('group_id', $groups)->get() as $user) {
            $hiscount = History::where('user_id', '=', $user->id)->count();

            // Temp Hard Coding of Group Requirements (Config Files To Come) (Upload in Bytes!) (Seedtime in Seconds!)

            // Leech ratio dropped below sites minimum
            if ($user->ratio < config('other.ratio') && $user->group_id != UserGroup::LEECH->value) {
                $user->group_id = UserGroup::LEECH->value;
                $user->can_request = false;
                $user->can_invite = false;
                $user->can_download = false;
                $user->save();
            }

            // User >= 0 and ratio above sites minimum
            if ($user->uploaded >= 0 && $user->ratio >= config('other.ratio') && $user->group_id != UserGroup::USER->value) {
                $user->group_id = UserGroup::USER->value;
                $user->can_request = true;
                $user->can_invite = true;
                $user->can_download = true;
                $user->save();
            }

            // PowerUser >= 1TiB and account 1 month old
            if ($user->uploaded >= $byteUnits->bytesFromUnit('1TiB') && $user->ratio >= config('other.ratio') && $user->created_at < $current->copy()->subDays(30)->toDateTimeString() && $user->group_id != UserGroup::POWERUSER->value) {
                $user->group_id = UserGroup::POWERUSER->value;
                $user->save();
            }

            // SuperUser >= 5TiB and account 2 month old
            if ($user->uploaded >= $byteUnits->bytesFromUnit('5TiB') && $user->ratio >= config('other.ratio') && $user->created_at < $current->copy()->subDays(60)->toDateTimeString() && $user->group_id != UserGroup::SUPERUSER->value) {
                $user->group_id = UserGroup::SUPERUSER->value;
                $user->save();
            }

            // ExtremeUser >= 20TiB and account 3 month old
            if ($user->uploaded >= $byteUnits->bytesFromUnit('20TiB') && $user->ratio >= config('other.ratio') && $user->created_at < $current->copy()->subDays(90)->toDateTimeString() && $user->group_id != UserGroup::EXTREMEUSER->value) {
                $user->group_id = UserGroup::EXTREMEUSER->value;
                $user->save();
            }

            // InsaneUser >= 50TiB and account 6 month old
            if ($user->uploaded >= $byteUnits->bytesFromUnit('50TiB') && $user->ratio >= config('other.ratio') && $user->created_at < $current->copy()->subDays(180)->toDateTimeString() && $user->group_id != UserGroup::INSANEUSER->value) {
                $user->group_id = UserGroup::INSANEUSER->value;
                $user->save();
            }

            // Seeder Seedsize >= 5TiB and account 1 month old and seedtime average 30 days or better
            if ($user->seedingTorrents()->sum('size') >= $byteUnits->bytesFromUnit('5TiB') && $user->ratio >= config('other.ratio') && round($user->history()->sum('seedtime') / max(1, $hiscount)) > 2_592_000 && $user->created_at < $current->copy()->subDays(30)->toDateTimeString() && $user->group_id != UserGroup::SEEDER->value) {
                $user->group_id = UserGroup::SEEDER->value;
                $user->save();
            }

            // Veteran >= 100TiB and account 1 year old
            if ($user->uploaded >= $byteUnits->bytesFromUnit('100TiB') && $user->ratio >= config('other.ratio') && $user->created_at < $current->copy()->subDays(365)->toDateTimeString() && $user->group_id != UserGroup::VETERAN->value) {
                $user->group_id = UserGroup::VETERAN->value;
                $user->save();
            }

            // Archivist Seedsize >= 10TiB and account 3 month old and seedtime average 60 days or better
            if ($user->seedingTorrents()->sum('size') >= $byteUnits->bytesFromUnit('10TiB') && $user->ratio >= config('other.ratio') && round($user->history()->sum('seedtime') / max(1, $hiscount)) > 2_592_000 * 2 && $user->created_at < $current->copy()->subDays(90)->toDateTimeString() && $user->group_id != UserGroup::ARCHIVIST->value) {
                $user->group_id = UserGroup::ARCHIVIST->value;
                $user->save();
            }

            if ($user->wasChanged()) {
                cache()->forget('user:'.$user->passkey);

                Unit3dAnnounce::addUser($user);
            }
        }

        $this->comment('Automated User Group Command Complete');
    }
}
