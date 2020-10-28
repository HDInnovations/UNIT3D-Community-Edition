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

use Exception;
use App\Models\Tv;
use App\Models\Movie;
use App\Models\Torrent;
use Illuminate\Console\Command;
use App\Services\Tmdb\TMDBScraper;

class FetchMeta extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'fetch:meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetchs Meta Data For New System On Preexxsisting Torrents';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->alert('Meta Fetcher Started');

        $client = new TMDBScraper();
        $torrents = Torrent::with('category')->select('tmdb', 'category_id')->whereNotNull('tmdb')->where('tmdb', '!=', 0)->oldest()->get();
        foreach ($torrents as $torrent) {
            if ($torrent->category->tv_meta) {
                $meta = Tv::where('id', '=', $torrent->tmdb)->first();
                if ($meta) {
                    $this->info('TV Already In DB');
                } else {
                    $client->tv($torrent->tmdb);
                    $this->info('TV Fetched');
                }
            }

            if ($torrent->category->movie_meta) {
                $meta = Movie::where('id', '=', $torrent->tmdb)->first();
                if ($meta) {
                    $this->info('Movie Already In DB');
                } else {
                    $client->movie($torrent->tmdb);
                    $this->info('Movie Fetched');
                }
            }
        }
        $this->alert('Meta Fetcher Complete');
    }
}
