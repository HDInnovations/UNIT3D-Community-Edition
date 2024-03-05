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

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('auto:upsert_peers')->everyFiveSeconds();
Schedule::command('auto:upsert_histories')->everyFiveSeconds();
Schedule::command('auto:upsert_announces')->everyFiveSeconds();
Schedule::command('auto:update_user_last_actions')->everyFiveSeconds();
Schedule::command('auto:delete_stopped_peers')->everyTwoMinutes();
Schedule::command('auto:cache_user_leech_counts')->everyThirtyMinutes();
Schedule::command('auto:group ')->daily();
Schedule::command('auto:nerdstat ')->hourly();
Schedule::command('auto:cache_random_media')->hourly();
Schedule::command('auto:reward_resurrection')->daily();
Schedule::command('auto:highspeed_tag')->hourly();
Schedule::command('auto:prewarning')->hourly();
Schedule::command('auto:warning')->daily();
Schedule::command('auto:deactivate_warning')->hourly();
Schedule::command('auto:flush_peers')->hourly();
Schedule::command('auto:bon_allocation')->hourly();
Schedule::command('auto:remove_personal_freeleech')->hourly();
Schedule::command('auto:remove_featured_torrent')->hourly();
Schedule::command('auto:recycle_invites')->daily();
Schedule::command('auto:recycle_activity_log')->daily();
Schedule::command('auto:recycle_failed_logins')->daily();
Schedule::command('auto:disable_inactive_users')->daily();
Schedule::command('auto:softdelete_disabled_users')->daily();
Schedule::command('auto:recycle_claimed_torrent_requests')->daily();
Schedule::command('auto:correct_history')->daily();
Schedule::command('auto:sync_peers')->everyFiveMinutes();
Schedule::command('auto:email-blacklist-update')->weekends();
Schedule::command('auto:reset_user_flushes')->daily();
Schedule::command('auto:stats_clients')->daily();
Schedule::command('auto:remove_torrent_buffs')->hourly();
Schedule::command('auto:refund_download')->daily();
Schedule::command('auto:torrent_balance')->hourly();
Schedule::command('auth:clear-resets')->daily();
//Schedule::command('auto:ban_disposable_users')->weekends();
//Schedule::command('backup:clean')->daily();
//Schedule::command('backup:run --only-db')->daily();
