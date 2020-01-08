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

namespace App\Services\Data;

use Carbon\Carbon;
use DateTime;

class Movie
{
    public string $imdb;

    public int $tmdb;

    public string $title;

    public array $aka = [];

    public \Carbon\Carbon $releaseDate;

    public int $releaseYear;

    public string $plot;

    public array $countries = [];

    public string $language;

    public array $languages = [];

    public array $genres = [];

    public int $runtime;

    public array $actors = [];

    public array $directors = [];

    public array $writers = [];

    public array $producers = [];

    public string $poster;

    public array $posters = [];

    public string $backdrop;

    public array $backdrops = [];

    public string $videoTrailer;

    public string $wikiUrl;

    public string $rated;

    public float $tmdbRating;

    public int $tmdbVotes;

    public float $imdbRating;

    public int $imdbVotes;

    public int $recommendations;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if (is_array($value) && !count($value)) {
                    $value = null;
                }
                $this->$key = $value;
            }
        }

        if ($this->releaseDate instanceof DateTime) {
            $release_date = $this->releaseDate ? (new Carbon())->instance($this->releaseDate) : null;
        } else {
            $release_date = $this->releaseDate ? new Carbon($this->releaseDate) : null;
        }
        $this->releaseDate = $release_date;
        $this->releaseYear = $release_date ? $release_date->year : null;

        $this->title = $this->cleanTitle($this->title);

        $this->genres = !empty($this->genres) ? $this->cleanGenres($this->genres) : null;
    }

    public function merge(self $data, self $data2 = null): self
    {
        $movies = func_get_args();

        foreach ($movies as $movie) {
            foreach ($movie as $movie_key => $movie_value) {
                if ($movie_key == 'title' && $movie_value != $this->title) {
                    $this->aka[] = $movie_value;
                }

                if ($movie_key == 'genres' && !empty($movie_value)) {
                    $this->genreMerge($movie_value);
                }

                if (empty($this->$movie_key)) {
                    $this->$movie_key = $movie_value;
                }
            }
        }

        if (!empty($this->aka)) {
            $this->aka = $this->removeSimilar($this->aka, $this->title, 90);
        }

        $this->genres = is_array($this->genres) ? array_unique($this->genres) : $this->genres;

        return $this;
    }

    private function cleanTitle($title)
    {
        if (strlen($title) > 4) {
            $might_be_year_one = str_replace(substr($title, 0, -6), '', $title);
            $might_be_year = str_replace(['(', ')'], '', $might_be_year_one);
            if ($might_be_year > 1_900 && $might_be_year < (date('Y') + 100)) {
                $title = trim(str_replace($might_be_year_one, '', $title));
            }
        }

        return $title;
    }

    /**
     * @return mixed[]
     */
    private function cleanGenres($genres): array
    {
        $genres = new Genre((array) $genres);

        return $genres->genres;
    }

    /**
     * Remove similar data from array using similar_text.
     *
     * @param $data
     * @param null|string $title
     * @param $diff
     *
     * @return array
     */
    private function removeSimilar($data, ?string $title, $diff): array
    {
        if ($title) {
            foreach ($data as $key => $value) {
                similar_text($title, $value, $percent);
                if ($percent > $diff) {
                    $data[$key] = null;
                }
            }
        }

        if (is_array($data)) {
            $data = array_filter($data);
            $data = array_unique($data);
        }

        foreach ($data as $keyOne => $valueOne) {
            foreach ($data as $keyTwo => $valueTwo) {
                if ($keyOne != $keyTwo) {
                    similar_text($valueOne, $valueTwo, $percent);
                    if ($percent > $diff) {
                        $data[$keyTwo] = null;
                    }
                }
            }
        }

        if (is_array($data)) {
            $data = array_filter($data);
            $data = array_unique($data);
        }

        return $data;
    }

    private function genreMerge($genres): void
    {
        if (empty($this->genres) && $genres) {
            $this->genres = [];
        }

        if (is_array($genres)) {
            foreach ($genres as $genre) {
                $this->genres[] = $genre;
            }
        } else {
            $this->genres[] = $genres;
        }
    }
}
