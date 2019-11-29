<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Services;

use App\Services\Clients\OmdbClient;
use App\Services\Clients\TmdbClient;
use App\Services\Data\Movie;
use App\Services\Data\Tv;

class MovieScrapper
{
    private $tmdbClient;

    private $omdbClient;

    public function __construct($tmdb_key = null, $tvdb_key = null, $omdb_key = null)
    {
        $this->tmdbClient = new TmdbClient($tmdb_key);
        $this->omdbClient = new OmdbClient($omdb_key);
    }

    /**
     * @param $type
     * @param  null  $imdb
     * @param  null  $tmdb
     * @param  null  $tvdb
     *
     * @return Movie|Tv
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     */
    public function scrape($type, $imdb = null, $tmdb = null, $tvdb = null)
    {
        if (! $imdb && ! $tmdb && ! $tvdb) {
            throw new \ErrorException('Either $imdb, $tmdb or $tvdb is required');
        }

        if ($type == 'movie') {
            $tmdb_movie = new Movie();

            if ($tmdb) {
                $tmdb_movie = $this->tmdbClient->movie($tmdb);
            }

            return $tmdb_movie;
        }

        if ($type == 'tv') {
            $tmdb_tv = new Tv();

            if ($tmdb) {
                $tmdb_tv = $this->tmdbClient->tv($tmdb);
            }

            return $tmdb_tv;
        }
    }

    public function person($tmdb)
    {
        return $this->tmdbClient->person($tmdb);
    }
}
