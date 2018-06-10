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

namespace App\Services\Clients;

use App\Services\Contracts\MovieTvInterface;
use App\Services\Data\Movie;
use App\Services\Data\Person;
use App\Services\Data\Tv;

class TmdbClient extends Client implements MovieTvInterface
{
    protected $apiUrl = 'api.themoviedb.org/3/';
    protected $apiSecure = true;

    private $imagePath = 'https://image.tmdb.org/t/p/w780';
    private $imageBackdropPath = 'https://image.tmdb.org/t/p/w1280';
    private $imageProfilePath = 'https://image.tmdb.org/t/p/h632';

    public function __construct($apiKey)
    {
        parent::__construct($this->apiUrl, $apiKey);
    }

    /**
     * @param array $keys
     * @param string $type
     * @return Movie
     * @throws \HttpInvalidParamException
     */
    public function find($keys, $type = 'movie')
    {
        $this->validateKeys($keys);

        if ($type == 'movie') {
            if (isset($keys['imdb'])) {
                $url = $this->apiUrl . 'find/' . $keys['imdb'] . '?api_key=' . $this->apiKey . '&external_source=imdb_id&language=' . config('app.locale');
                $results = $this->toArray($this->request($url));

                if (isset($results['movie_results'][0]['id'])) {
                    return $this->movie($results['movie_results'][0]['id']);
                }

                return new Movie();
            }
        }

        if ($type == 'tv') {
            $url = null;
            if (isset($keys['imdb'])) {
                $url = $this->apiUrl . 'find/' . $keys['imdb'] . '?api_key=' . $this->apiKey . '&external_source=imdb_id';
            }
            if (isset($keys['tvdb'])) {
                $url = $this->apiUrl . 'find/' . $keys['tvdb'] . '?api_key=' . $this->apiKey . '&external_source=tvdb_id';
            }
            if ($url) {
                $results = $this->toArray($this->request($url));
                if (isset($results['tv_results'][0]['id'])) {
                    return $this->tv($results['tv_results'][0]['id']);
                }
            }

            return new Tv();
        }

        throw new \HttpInvalidParamException('What are you trying to find?');
    }

    /**
     * @param $id
     * @param string $type
     * @return Movie
     */
    public function movie($id, $type = 'movie')
    {
        $this->validateKeys(['tmdb' => $id]);

        $url = $this->apiUrl . $type . '/' . $id . '?append_to_response=alternative_titles,credits,videos,images,keywords,external_ids&api_key=' . $this->apiKey .'&language=' . config('app.locale');
        $movie = $this->toArray($this->request($url));

//        dump($movie);

        if ($type == 'tv') {
            return $this->formatTv($movie);
        }

        return $this->formatMovie($movie);
    }

    public function tv($id)
    {
        return $this->movie($id, 'tv');
    }

    public function person($id)
    {
        $this->validateKeys(['tmdb' => $id]);

        $url = $this->apiUrl . 'person/' . $id . '?append_to_response=external_ids,images&api_key=' . $this->apiKey .'&language=' . config('app.locale');

        return $this->formatPerson($this->toArray($this->request($url)));
    }

    /**
     * @param $movie
     * @return Movie
     */
    private function formatMovie($movie)
    {
        return new Movie([
            'imdb' => $movie['imdb_id'],
            'tmdb' => $movie['id'],
            'title' => $movie['title'],
            'releaseDate' => $movie['release_date'],
            'plot' => $movie['overview'],
            'aka' => $this->formatAlternativeTitles($movie),
            'countries' => $this->formatCountries($movie['production_countries']),
            'language' => !empty($movie['original_language']) ? ['code' => $movie['original_language'], 'language' => null] : 'Not Defined',
            'languages' => $this->formatLanguages($movie['spoken_languages']),
            'genres' => $this->formatGenres($movie['genres']),
            'videoTrailer' => $this->formatVideoTrailers($movie),
            'runtime' => $movie['runtime'],
            'actors' => $this->formatCasts($movie['credits'], 'actors'),
            'directors' => $this->formatCasts($movie['credits'], 'directors'),
            'writers' => $this->formatCasts($movie['credits'], 'writers'),
            'producers' => $this->formatCasts($movie['credits'], 'producers'),
            'poster' => !empty($movie['poster_path']) ? $this->imagePath . $movie['poster_path'] : 'https://via.placeholder.com/600x900',
            'posters' => !empty($movie['images']['posters']) ? $this->formatImages(
                $movie['images']['posters'],
                $this->imagePath,
                $movie['poster_path']
            ) : 'https://via.placeholder.com/600x900',
            'backdrop' => !empty($movie['backdrop_path']) ? $this->imageBackdropPath . $movie['backdrop_path'] : 'https://via.placeholder.com/1400x800',
            'backdrops' => !empty($movie['images']['backdrops']) ? $this->formatImages(
                $movie['images']['backdrops'],
                $this->imageBackdropPath,
                $movie['backdrop_path']
            ) : 'https://via.placeholder.com/1400x800',
            'tmdbRating' => $movie['vote_average'],
            'tmdbVotes' => $movie['vote_count'],
        ]);
    }

    private function formatTv($movie)
    {
        return new Tv([
            'tmdb' => $movie['id'],
            'imdb' => !empty($movie['external_ids']['imdb_id']) ? $movie['external_ids']['imdb_id'] : 'Not Defined',
            'tvdb' => !empty($movie['external_ids']['tvdb_id']) ? $movie['external_ids']['tvdb_id'] : 'Not Defined',
            'title' => $movie['name'],
            'releaseDate' => $movie['first_air_date'],
            'endDate' => ($movie['status'] == 'Ended') ? $movie['last_air_date'] : null,
            'plot' => $movie['overview'],
            'actors' => $this->formatCasts($movie['credits'], 'actors'),
            'directors' => $this->formatCasts($movie['credits'], 'directors'),
            'writers' => $this->formatCasts($movie['credits'], 'writers'),
            'producers' => $this->formatCasts($movie['credits'], 'producers'),
            'creators' => $this->formatCasts($movie['created_by'], 'creators'),
            'aka' => $this->formatAlternativeTitles($movie),
            'countries' => $this->formatCountries($movie['origin_country'], 'tv'),
            'language' => !empty($movie['original_language']) ? ['code' => $movie['original_language'], 'language' => null] : 'Not Defined',
            'languages' => $this->formatLanguages($movie['languages'], 'tv'),
            'genres' => $this->formatGenres($movie['genres']),
            'videoTrailer' => $this->formatVideoTrailers($movie),
            'poster' => !empty($movie['poster_path']) ? $this->imagePath . $movie['poster_path'] : 'https://via.placeholder.com/600x900',
            'posters' => !empty($movie['images']['posters']) ? $this->formatImages(
                $movie['images']['posters'],
                $this->imagePath,
                $movie['poster_path']
            ) : 'https://via.placeholder.com/600x900',
            'backdrop' => !empty($movie['backdrop_path']) ? $this->imageBackdropPath . $movie['backdrop_path'] : 'https://via.placeholder.com/1400x800',
            'backdrops' => !empty($movie['images']['backdrops']) ? $this->formatImages(
                $movie['images']['backdrops'],
                $this->imageBackdropPath,
                $movie['backdrop_path']
            ) : 'https://via.placeholder.com/1400x800',
            'tmdbRating' => $movie['vote_average'],
            'tmdbVotes' => $movie['vote_count'],
        ]);
    }

    /**
     * @param $person
     * @return Person
     */
    private function formatPerson($person)
    {
        return new Person([
            'imdb' => $person['imdb_id'],
            'tmdb' => $person['id'],
            'name' => $person['name'],
            //'aka' => $person['also_known_as'],
            //'gender' => $person['gender'] == 1 ? 'female' : ($person['gender'] == 2 ? 'male' : null),
            //'birthday' => $person['birthday'],
            //'deathday' => $person['deathday'],
            //'placeOfBirth' => $person['place_of_birth'],
            //'biography' => $person['biography'],
            'photo' => !empty($person['profile_path']) ? $this->imageProfilePath . $person['profile_path'] : 'https://via.placeholder.com/100x100',
            'photos' => !empty($person['images']['profiles']) ? $this->formatImages(
                $person['images']['profiles'],
                $this->imageProfilePath,
                $person['profile_path']
            ) : null,
        ]);
    }

    private function formatCountries($countries, $type = 'movie')
    {
        $movie_countries = [];
        if ($type == 'movie') {
            if (! is_null($countries)) {
                foreach ($countries as $country) {
                    $movie_countries[] = [
                        'code' => $country['iso_3166_1'],
                        'country' => $country['name'],
                    ];
                }
            }
        }

        if ($type == 'tv') {
            if (! is_null($countries)) {
                foreach ($countries as $country) {
                    $movie_countries[] = [
                        'code' => $country,
                        'country' => null,
                    ];
                }
            }
        }

        return $movie_countries;
    }

    private function formatLanguages($languages, $type = 'movie')
    {
        $movie_languages = [];
        if ($type == 'movie') {
            if (! is_null($languages)) {
                foreach ($languages as $language) {
                    $movie_languages[] = [
                        'code' => $language['iso_639_1'],
                        'language' => $language['name'],
                    ];
                }
            }
        }
        if ($type == 'tv') {
            if (! is_null($languages)) {
                foreach ($languages as $language) {
                    $movie_languages[] = [
                        'code' => $language,
                        'language' => null,
                    ];
                }
            }
        }

        return $movie_languages;
    }

    private function formatGenres($genres)
    {
        $movie_genres = [];
        if (! is_null($genres)) {
            foreach ($genres as $genre) {
                $movie_genres[] = $genre['name'];
            }
        }

        return $movie_genres;
    }

    private function formatAlternativeTitles($movie)
    {
        $akas = [];

        if (!empty($movie['original_title'])) {
            if (strtolower($movie['original_title']) != strtolower($movie['title'])) {
                $akas[] = $movie['original_title'];
            }
        }

        if (!empty($movie['original_name'])) {
            if (strtolower($movie['original_name']) != strtolower($movie['name'])) {
                $akas[] = $movie['original_name'];
            }
        }

        $original_title = !empty($movie['title']) ? $movie['title'] : (!empty($movie['name']) ? $movie['name'] : null);

        $alternative_titles = null;
        if (!empty($movie['alternative_titles']['titles'])) {
            $alternative_titles = $movie['alternative_titles']['titles'];
        }
        if (!empty($movie['alternative_titles']['results'])) {
            $alternative_titles = $movie['alternative_titles']['results'];
        }
        if ($alternative_titles) {
            foreach ($alternative_titles as $aka_title) {
                similar_text($original_title, $aka_title['title'], $percent);
                if ($percent < 95) {
                    $akas[] = $aka_title['title'];
                }
            }
        }

        if (is_array($akas)) {
            $akas = array_filter($akas);
            $akas = array_unique($akas);
        }

        return $akas;
    }

    private function formatVideoTrailers($movie)
    {
        if (!empty($trailers = $movie['videos']['results'])) {
            foreach ($trailers as $trailer) {
                if ($trailer['type'] == 'Trailer' && $trailer['site'] == 'YouTube') {
                    return 'https://www.youtube.com/watch?v=' . $trailer['key'];
                }
            }
        }

        return null;
    }

    private function formatImages($images, $path, $image)
    {
        $images = array_map(function ($item) use ($path) {
            return $path . $item['file_path'];
        }, $images);

        $images = array_filter($images, function ($item) use ($path, $image) {
            if ($item == $path . $image) {
                return false;
            }

            return true;
        });

        return $images;
    }

    private function formatCasts($credits, $role)
    {
        $casts = [];
        if ($role == 'actors') {
            if (!empty($credits['cast'])) {
                foreach ($credits['cast'] as $credit) {
                    $casts[] = new Person([
                        'tmdb' => $credit['id'],
                        'name' => $credit['name'],
                        'character' => $credit['character'],
                        'order' => $credit['order'],
                    ]);
                }
            }
        } else {
            if ($role == 'creators' && !empty($credits)) {
                foreach ($credits as $credit) {
                    $casts[] = new Person([
                        'tmdb' => $credit['id'],
                        'name' => $credit['name'],
                    ]);
                }
            }
            if (!empty($credits['crew'])) {
                foreach ($credits['crew'] as $credit) {
                    if ($role == 'directors' && $credit['department'] == 'Directing') {
                        $casts[] = new Person([
                            'tmdb' => $credit['id'],
                            'name' => $credit['name'],
                            'job' => $credit['job'],
                        ]);
                    } elseif ($role == 'writers' && $credit['department'] == 'Writing') {
                        $casts[] = new Person([
                            'tmdb' => $credit['id'],
                            'name' => $credit['name'],
                            'job' => $credit['job'],
                        ]);
                    } elseif ($role == 'producers' && $credit['department'] == 'Production') {
                        $casts[] = new Person([
                            'tmdb' => $credit['id'],
                            'name' => $credit['name'],
                            'job' => $credit['job'],
                        ]);
                    }
                }
            }
        }

        return $casts;
    }
}
