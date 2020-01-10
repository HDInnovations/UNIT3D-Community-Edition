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

namespace App\Services\Clients;

use App\Services\Contracts\MovieTvInterface;
use App\Services\Data\Movie;
use App\Services\Data\Tv;

final class OmdbClient extends Client implements MovieTvInterface
{
    /**
     * @var string
     */
    protected string $apiUrl = 'www.omdbapi.com';

    /**
     * @var bool
     */
    protected bool $apiSecure = true;

    public function __construct($apiKey = null)
    {
        parent::__construct($this->apiUrl, $apiKey);
    }

    /**
     * @param  array  $keys
     * @param  string|null  $type
     *
     * @return mixed[]
     * @throws \HttpResponseException
     */
    public function find(array $keys, ?string $type = null): array
    {
        $this->validateKeys($keys);

        $url = $this->apiUrl.'/?i='.$keys['imdb'].'&plot=full&r=json&apikey='.$this->apiKey;

        $result = $this->toArray($this->request($url));
        if (isset($result['Response']) && $result['Response'] == 'True') {
            return array_map(function ($value) {
                if ($value == 'N/A') {
                    return;
                }

                return $value;
            }, $result);
        }
    }

    public function movie($id)
    {
        return $this->formatMovie($this->find(['imdb' => $id], 'movie'), 'movie');
    }

    public function tv($id)
    {
        return $this->formatMovie($this->find(['imdb' => $id], 'series'), 'series');
    }

    public function person($id): array
    {
        //
    }

    private function formatMovie($movie, $type = 'movie'): \App\Services\Data\Movie
    {
        if (is_array($movie) && $movie['Type'] != $type) {
            return ($type == 'movie') ? new Movie([]) : new Tv([]);
        }

        $data = [
            'imdb'         => !empty($movie['imdbID']) ? $movie['imdbID'] : 'Not Defined',
            'title'        => $movie['Title'],
            'releaseDate'  => $movie['Released'],
            'plot'         => $movie['Plot'],
            'languages'    => $this->formatLanguages($movie['Language']),
            'genres'       => $this->formatGenres($movie['Genre']),
            'runtime'      => (float) $movie['Runtime'],
            'poster'       => $this->resizePoster($movie['Poster']),
            'videoTrailer' => null,
            'wikiUrl'      => null,
            'rated'        => $movie['Rated'],
            'imdbRating'   => $movie['imdbRating'],
            'imdbVotes'    => str_replace(',', '', $movie['imdbVotes']),
        ];

        return ($type == 'movie') ? new Movie($data) : new Tv($data);
    }

    /**
     * @param $languages
     * @return string[][]|null[][]
     */
    private function formatLanguages($languages): array
    {
        $movie_languages = [];
        if (!empty($languages)) {
            $languages = explode(',', $languages);
            foreach ($languages as $language) {
                $movie_languages[] = [
                    'code'     => null,
                    'language' => trim($language),
                ];
            }
        }

        return $movie_languages;
    }

    /**
     * @param $genres
     * @return string[]
     */
    private function formatGenres($genres): array
    {
        $movie_genres = [];
        if (!empty($genres)) {
            $genres = explode(',', $genres);
            foreach ($genres as $genre) {
                $movie_genres[] = trim($genre);
            }
        }

        return $movie_genres;
    }

    /**
     * @param $poster
     * @return mixed[]|string
     */
    private function resizePoster($poster)
    {
        return str_replace('SX300', 'SX780', $poster);
    }
}
