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

use App\Models\User;
use Illuminate\Console\Command;
use Exception;

class AutoCacheUserLeechCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:cache_user_leech_counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches user leech counts';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $peerCounts = User::withoutGlobalScopes()
            ->selectRaw("CONCAT('user-leeching-count:', id) as cacheKey")
            ->selectRaw('(select COUNT(*) from peers where peers.user_id = users.id and seeder = 0 and active = 1 and visible = 1) as count')
            ->pluck('count', 'cacheKey')
            ->toArray();

        cache()->putMany($peerCounts);

        $this->comment(\count($peerCounts).' user leech counts cached.');
    }
}
