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

use App\Models\TagTorrent;
use App\Models\Torrent;
use App\Services\MovieScrapper;
use Illuminate\Console\Command;

class FetchGenres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:genres';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Genres For Torrents In DB';

    /**
     * Execute the console command.
     *
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));

        $torrents = Torrent::withAnyStatus()
            ->select(['id', 'category_id', 'imdb', 'tmdb'])
            ->get();

        foreach ($torrents as $torrent) {
            if ($torrent->category->tv_meta) {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $meta = $client->scrape('tv', null, $torrent->tmdb);
                } else {
                    $meta = $client->scrape('tv', 'tt'.$torrent->imdb);
                }
            }
            if ($torrent->category->movie_meta) {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $meta = $client->scrape('movie', null, $torrent->tmdb);
                } else {
                    $meta = $client->scrape('movie', 'tt'.$torrent->imdb);
                }
            }

            if ($meta->genres) {
                foreach ($meta->genres as $genre) {
                    $tag = new TagTorrent();
                    $tag->torrent_id = $torrent->id;
                    $tag->tag_name = $genre;
                    $tag->save();
                }
            }

            // sleep for 1 second
            sleep(1);
        }
        $this->comment('Torrent Genres Command Complete');
    }
}
