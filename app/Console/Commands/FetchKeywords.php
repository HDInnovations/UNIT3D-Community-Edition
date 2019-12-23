<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Console\Commands;

use App\Models\Keyword;
use App\Models\Torrent;
use App\Services\MovieScrapper;
use Illuminate\Console\Command;

class FetchKeywords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:keywords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Keywprds For Torrents In DB';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \ErrorException
     */
    public function handle()
    {
        $client = new MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        $appurl = config('app.url');

        $torrents = Torrent::withAnyStatus()
            ->with(['category'])
            ->select(['id', 'slug', 'name', 'category_id', 'imdb', 'tmdb'])
            ->get();

        foreach ($torrents as $torrent) {
            $meta = null;

            if ($torrent->category->tv_meta) {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $meta = $client->scrape('tv', null, $torrent->tmdb);
                } else {
                    $meta = $client->scrape('tv', 'tt'.$torrent->imdb);
                }
                if ($meta->keywords) {
                    foreach ($meta->keywords as $keyword) {
                        $tag = new Keyword();
                        $tag->torrent_id = $torrent->id;
                        $tag->name = $keyword;
                        $tag->save();
                    }
                    $this->info("({$torrent->category->name}) Keywords Fetched For Torrent {$torrent->name} \n");
                } else {
                    $this->warn("({$torrent->category->name}) NoKeywords Found For Torrent {$torrent->name}
                    {$appurl}/torrents/{$torrent->id} \n");
                }
            }

            if ($torrent->category->movie_meta) {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $meta = $client->scrape('movie', null, $torrent->tmdb);
                } else {
                    $meta = $client->scrape('movie', 'tt'.$torrent->imdb);
                }
                if ($meta->keywords) {
                    foreach ($meta->keywords as $keyword) {
                        $tag = new Keyword();
                        $tag->torrent_id = $torrent->id;
                        $tag->name = $keyword;
                        $tag->save();
                    }
                    $this->info("({$torrent->category->name}) Keywords Fetched For Torrent {$torrent->name} \n");
                } else {
                    $this->warn("({$torrent->category->name}) No Keywords Found For Torrent {$torrent->name}
                    {$appurl}/torrents/{$torrent->id} \n");
                }
            }

            if ($torrent->category->no_meta || $torrent->category->music_meta || $torrent->category->game_meta) {
                $this->warn("(SKIPPED) {$torrent->name} Is In A Category That Does Not Have Meta. \n");
            }

            // sleep for 1 second
            sleep(1);
        }
    }
}
