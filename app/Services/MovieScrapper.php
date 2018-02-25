<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Services;

use App\Services\Clients\OmdbClient;
use App\Services\Clients\TmdbClient;
use App\Services\Clients\TvdbClient;
use App\Services\Data\Movie;
use App\Services\Data\Tv;

class MovieScrapper
{
    private $tmdbClient;
    private $omdbClient;
    private $tvdbClient;

    public function __construct($tmdb_key = null, $tvdb_key = null, $omdb_key = null)
    {
        $this->tmdbClient = new TmdbClient($tmdb_key);
        $this->omdbClient = new OmdbClient($omdb_key);
        $this->tvdbClient = new TvdbClient($tvdb_key);
    }

    /**
     * @param $type
     * @param null $imdb
     * @param null $tmdb
     * @param null $tvdb
     * @return Movie|Tv
     * @throws \ErrorException
     */
    public function scrape($type, $imdb = null, $tmdb = null, $tvdb = null)
    {
        if (!$imdb && !$tmdb && !$tvdb) {
            throw new \ErrorException('Either $imdb, $tmdb or $tvdb is required');
        }

        if ($type == 'movie') {
            $omdb_movie = $tmdb_movie = new Movie();

            if ($tmdb) {
                $tmdb_movie = $this->tmdbClient->movie($tmdb);
                $imdb = empty($imdb) ? $tmdb_movie->imdb : $imdb;
            }

            if ($imdb) {
                if (!$tmdb_movie->title) {
                    $tmdb_movie = $this->tmdbClient->find(['imdb' => $imdb], $type);
                }
                $omdb_movie = $this->omdbClient->movie($imdb);
            }

            return $tmdb_movie->merge($omdb_movie);
        }

        if ($type == 'tv') {
            $omdb_tv = $tmdb_tv = new Tv();

            if ($tmdb) {
                $tmdb_tv = $this->tmdbClient->tv($tmdb);
                $imdb = empty($imdb) ? $tmdb_tv->imdb : $imdb;
            }

            if ($imdb) {
                if (!$tmdb_tv->title) {
                    $tmdb_tv = $this->tmdbClient->find(['imdb' => $imdb], 'tv');
                }
                $omdb_tv = $this->omdbClient->tv($imdb);
            }

            return $tmdb_tv->merge($omdb_tv);
        }
    }

    public function person($tmdb)
    {
        return $this->tmdbClient->person($tmdb);
    }
}
