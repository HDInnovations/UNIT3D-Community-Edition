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

namespace App\Services\Data;

use Carbon\Carbon;

class Movie
{
    /**
     * @var string
     */
    public $imdb;

    /**
     * @var int
     */
    public $tmdb;

    /**
     * @var string
     */
    public $title;

    /**
     * @var array
     */
    public $aka;

    /**
     * @var Carbon
     */
    public $releaseDate;

    /**
     * @var int
     */
    public $releaseYear;

    /**
     * @var string
     */
    public $plot;

    /**
     * @var array
     */
    public $countries;

    /**
     * @var string
     */
    public $language;

    /**
     * @var array
     */
    public $languages;

    /**
     * @var array
     */
    public $genres;

    /**
     * @var int
     */
    public $runtime;

    /**
     * @var array
     */
    public $actors;

    /**
     * @var array
     */
    public $directors;

    /**
     * @var array
     */
    public $writers;

    /**
     * @var array
     */
    public $producers;

    /**
     * @var string
     */
    public $poster;

    /**
     * @var array
     */
    public $posters;

    /**
     * @var string
     */
    public $backdrop;

    /**
     * @var array
     */
    public $backdrops;

    /**
     * @var string
     */
    public $videoTrailer;

    /**
     * @var string
     */
    public $wikiUrl;

    /**
     * @var string
     */
    public $rated;

    /**
     * @var float
     */
    public $tmdbRating;

    /**
     * @var int
     */
    public $tmdbVotes;

    /**
     * @var float
     */
    public $imdbRating;

    /**
     * @var int
     */
    public $imdbVotes;

    /**
     * @var int
     */
    public $recommendations;

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

        if ($this->releaseDate instanceof \DateTime) {
            $release_date = $this->releaseDate ? (new Carbon())->instance($this->releaseDate) : null;
        } else {
            $release_date = $this->releaseDate ? new Carbon($this->releaseDate) : null;
        }
        $this->releaseDate = $release_date;
        $this->releaseYear = $release_date ? $release_date->year : null;

        $this->title = $this->cleanTitle($this->title);

        $this->genres = !empty($this->genres) ? $this->cleanGenres($this->genres) : null;
    }

    public function merge(self $data, self $data2 = null)
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
            if ($might_be_year > 1900 && $might_be_year < (date('Y') + 100)) {
                $title = trim(str_replace($might_be_year_one, '', $title));
            }
        }

        return $title;
    }

    private function cleanGenres($genres)
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
    private function removeSimilar($data, $title, $diff)
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

    private function genreMerge($genres)
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
