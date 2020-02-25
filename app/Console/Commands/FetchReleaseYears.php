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

use App\Models\Torrent;
use App\Services\MovieScrapper;
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
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        $appurl = config('app.url');

        $torrents = Torrent::withAnyStatus()
            ->with(['category'])
            ->select(['id', 'slug', 'name', 'category_id', 'imdb', 'tmdb', 'release_year'])
            ->whereNull('release_year')
            ->get();

        $withyear = Torrent::withAnyStatus()
            ->whereNotNull('release_year')
            ->count();

        $withoutyear = Torrent::withAnyStatus()
            ->whereNull('release_year')
            ->count();

        $this->alert(sprintf('%s Torrents Already Have A Release Year Value', $withyear));
        $this->alert(sprintf('%s Torrents Are Missing A Release Year Value', $withoutyear));

        foreach ($torrents as $torrent) {
            $meta = null;

            if ($torrent->category->tv_meta) {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $meta = $client->scrape('tv', null, $torrent->tmdb);
                } else {
                    $meta = $client->scrape('tv', 'tt'.$torrent->imdb);
                }
                if (isset($meta->releaseYear) && $meta->releaseYear > '1900') {
                    $torrent->release_year = $meta->releaseYear;
                    $torrent->save();
                    $this->info(sprintf('(%s) Release Year Fetched For Torrent %s 
', $torrent->category->name, $torrent->name));
                } else {
                    $this->warn(sprintf('(%s) No Release Year Found For Torrent %s
                    %s/torrents/%s 
', $torrent->category->name, $torrent->name, $appurl, $torrent->id));
                }
            }

            if ($torrent->category->movie_meta) {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $meta = $client->scrape('movie', null, $torrent->tmdb);
                } else {
                    $meta = $client->scrape('movie', 'tt'.$torrent->imdb);
                }
                if (isset($meta->releaseYear) && $meta->releaseYear > '1900') {
                    $torrent->release_year = $meta->releaseYear;
                    $torrent->save();
                    $this->info(sprintf('(%s) Release Year Fetched For Torrent %s 
', $torrent->category->name, $torrent->name));
                } else {
                    $this->warn(sprintf('(%s) No Release Year Found For Torrent %s
                    %s/torrents/%s 
', $torrent->category->name, $torrent->name, $appurl, $torrent->id));
                }
            }

            if ($torrent->category->game_meta) {
                if ($torrent->igdb && $torrent->igdb != 0) {
                    $meta = Game::find($torrent->igdb);
                }
                if (isset($meta->first_release_date) && $meta->first_release_date > '1900') {
                    $torrent->release_year = date('Y', strtotime($meta->first_release_date));
                    $torrent->save();
                    $this->info(sprintf('(%s) Release Year Fetched For Torrent %s 
', $torrent->category->name, $torrent->name));
                } else {
                    $this->warn(sprintf('(%s) No Release Year Found For Torrent %s
                    %s/torrents/%s 
', $torrent->category->name, $torrent->name, $appurl, $torrent->id));
                }
            }

            if ($torrent->category->no_meta || $torrent->category->music_meta) {
                $this->warn(sprintf('(SKIPPED) %s Is In A Category That Does Not Have Meta. 
', $torrent->name));
            }

            // sleep for 1 second
            sleep(1);
        }
        $this->comment('Torrent Release Year Command Complete');
    }
}
