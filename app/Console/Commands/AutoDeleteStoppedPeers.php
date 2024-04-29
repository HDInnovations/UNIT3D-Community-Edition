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
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class AutoDeleteStoppedPeers.
 *
 * TThis class is responsible for deleting all stopped peers from the database.
 * It uses the DB facade to directly interact with the 'peers' table in the database.
 */
class AutoDeleteStoppedPeers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:delete_stopped_peers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all stopped peers';

    /**
     * Execute the console command.
     *
     * This method is the entry point of the command. It deletes all records from the 'peers' table
     * where 'active' is 0 and 'updated_at' is more than 2 hours ago.
     *
     * @throws Exception|Throwable If there is an error during the execution of the command.
     */
    public function handle(): void
    {
        // Start a database transaction.
        DB::transaction(static function (): void {
            DB::table('peers')
                ->where('active', '=', 0)
                ->where('updated_at', '>', now()->subHours(2))
                ->delete();
        }, 5); // 5 is the number of attempts if deadlock occurs.

        // Output a message to the console.
        $this->comment('Automated delete stopped peers command complete');
    }
}
