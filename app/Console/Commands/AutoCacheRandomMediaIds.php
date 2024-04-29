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

use App\Models\Movie;
use App\Models\Tv;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\Redis;
use Throwable;

/**
 * Class AutoCacheRandomMediaIds.
 *
 * This class is responsible for caching valid media ids for the random media component.
 * It is a console command that can be executed manually or scheduled.
 */
class AutoCacheRandomMediaIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:cache_random_media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches valid media ids for random media component';

    /**
     * Execute the console command.
     *
     * This method is the entry point of the command. It fetches the ids of movies and tv shows that have torrents and a backdrop,
     * and stores them in the cache for use by the random media component.
     *
     * @throws Exception|Throwable If there is an error during the execution of the command.
     */
    public function handle(): void
    {
        // Fetch the ids of movies that have torrents and a backdrop.
        $movieIds = Movie::query()
            ->select('id')
            ->whereHas('torrents')
            ->whereNotNull('backdrop')
            ->pluck('id');

        // Fetch the ids of tv shows that have torrents and a backdrop.
        $tvIds = Tv::query()
            ->select('id')
            ->whereHas('torrents')
            ->whereNotNull('backdrop')
            ->pluck('id');

        // Define the cache key for movies.
        $cacheKey = config('cache.prefix').':random-media-movie-ids';

        // Store the movie ids in the cache.
        Redis::connection('cache')->command('SADD', [$cacheKey, ...$movieIds]);

        // Define the cache key for tv shows.
        $cacheKey = config('cache.prefix').':random-media-tv-ids';

        // Store the tv show ids in the cache.
        Redis::connection('cache')->command('SADD', [$cacheKey, ...$tvIds]);

        // Output a success message to the console.
        $this->comment($movieIds->count().' movie ids and '.$tvIds->count().' tv ids cached.');
    }
}