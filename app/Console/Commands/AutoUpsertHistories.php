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

use App\Models\History;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Exception;

class AutoUpsertHistories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:upsert_histories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upserts peer histories in batches';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        /**
         * MySql can handle a max of 65535 placeholders per query,
         * and there are 16 fields on each history that are updated:.
         *
         * - user_id
         * - torrent_id
         * - agent
         * - uploaded
         * - actual_uploaded
         * - client_uploaded
         * - downloaded
         * - actual_downloaded
         * - client_downloaded
         * - seeder
         * - active
         * - seedtime
         * - immune
         * - completed_at
         * - created_at
         * - updated_at
         */
        $historiesPerCycle = intdiv(65_000, 16);

        $key = config('cache.prefix').':histories:batch';
        $historyCount = Redis::connection('announce')->command('LLEN', [$key]);

        for ($historiesLeft = $historyCount; $historiesLeft > 0; $historiesLeft -= $historiesPerCycle) {
            $histories = Redis::connection('announce')->command('LPOP', [$key, $historiesPerCycle]);
            $histories = array_map('unserialize', $histories);

            History::upsert(
                $histories,
                ['user_id', 'torrent_id'],
                [
                    'user_id',
                    'torrent_id',
                    'agent',
                    'uploaded',
                    'actual_uploaded',
                    'client_uploaded',
                    'downloaded',
                    'actual_downloaded',
                    'client_downloaded',
                    'seeder',
                    'active',
                    'seedtime',
                    'immune',
                    'completed_at',
                ],
            );
        }

        $this->comment('Automated upsert histories command complete');
    }
}
