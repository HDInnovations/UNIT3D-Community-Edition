<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Exception;
use Throwable;

/**
 * Class AutoUpdateUserLastActions.
 *
 * This class is responsible for updating the 'last_action' field of users in batches.
 * It fetches user IDs from a Redis list and updates the 'last_action' field for these users in the database.
 */
class AutoUpdateUserLastActions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:update_user_last_actions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates user last actions in batches';

    /**
     * Execute the console command.
     *
     * This method is the entry point of the command. It fetches user IDs from a Redis list,
     * then updates the 'last_action' field for these users in the database.
     *
     * @throws Exception|Throwable If there is an error during the execution of the command.
     */
    public function handle(): void
    {
        // The key of the Redis list that contains the user IDs.
        $key = config('cache.prefix').':user-last-actions:batch';

        // Get the number of user IDs in the Redis list.
        $userIdCount = Redis::command('LLEN', [$key]);

        // Fetch and remove the user IDs from the Redis list.
        $userIds = Redis::command('LPOP', [$key, $userIdCount]);

        // If there are user IDs, update the 'last_action' field for these users in the database.
        if ($userIds !== false) {
            DB::transaction(static function () use ($userIds): void {
                DB::table('users')
                    ->whereIntegerInRaw('id', $userIds)
                    ->update([
                        'last_action' => now(),
                    ]);
            }, 5); // 5 is the number of attempts if deadlock occurs.
        }

        // Output a message to the console.
        $this->comment('Automated upsert histories command complete');
    }
}
