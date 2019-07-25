<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Console\Commands;

use App\Models\Torrent;
use Illuminate\Console\Command;
use MarcReichel\IGDBLaravel\Models\Game;

class FetchReleaseYears extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:release_years';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Release Years For Torrents In DB';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \ErrorException
     */
    public function handle()
    {
        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));

        $torrents = Torrent::withAnyStatus()
            ->with(['category'])
            ->select(['id', 'category_id', 'imdb', 'tmdb', 'year'])
            ->whereNull('year')
            ->get();

        foreach ($torrents as $torrent) {
            $meta = null;

            if ($torrent->category->tv_meta) {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $meta = $client->scrape('tv', null, $torrent->tmdb);
                } else {
                    $meta = $client->scrape('tv', 'tt'.$torrent->imdb);
                }
                if (isset($meta->releaseYear)) {
                    $torrent->release_year = $meta->releaseYear;
                    $torrent->save();
                    $this->info("Release Year Fetched For Torrent {$torrent->name}");
                } else {
                    $this->alert("No Release Year Found For Torrent {$torrent->name}");
                }
            }

            if ($torrent->category->movie_meta) {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $meta = $client->scrape('movie', null, $torrent->tmdb);
                } else {
                    $meta = $client->scrape('movie', 'tt'.$torrent->imdb);
                }
                if (isset($meta->releaseYear)) {
                    $torrent->release_year = $meta->releaseYear;
                    $torrent->save();
                    $this->info("Release Year Fetched For Torrent {$torrent->name}");
                } else {
                    $this->alert("No Release Year Found For Torrent {$torrent->name}");
                }
            }

            if ($torrent->category->game_meta) {
                if ($torrent->igdb && $torrent->igdb != 0) {
                    $meta = Game::find($torrent->igdb);
                }
                if (isset($meta->first_release_date)) {
                    $torrent->release_year = date('Y', strtotime($meta->first_release_date));
                    $torrent->save();
                    $this->info("Release Year Fetched For Torrent {$torrent->name}");
                } else {
                    $this->alert("No Release Year Found For Torrent {$torrent->name}");
                }
            }

            // sleep for 2 seconds
            sleep(2);
        }
    }
}
