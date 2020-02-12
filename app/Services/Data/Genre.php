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

class Genre
{
    public $genres;

    protected $movieGenres = [
        'Action',
        'Adventure',
        'Animation',
        'Biography',
        'Comedy',
        'Crime',
        'Documentary',
        'Drama',
        'Family',
        'Fantasy',
        'History',
        'Horror',
        'Music',
        'Musical',
        'Mystery',
        'Romance',
        'Science Fiction',
        'Sport',
        'Thriller',
        'War',
        'Western',
    ];

    protected $tvGenres = [
        'Game-Show',
        'News',
        'Reality-TV',
        'Sitcom',
        'Talk-Show',
        'Thriller',
    ];

    public function __construct(array $genres)
    {
        $this->genres = $this->parseGenres($genres);
    }

    private function parseGenres($genres)
    {
        $myGenre = [];
        $genreCollection = $this->movieGenres + $this->tvGenres;
        foreach ($genres as $genre) {
            if (in_array($genre, $genreCollection)) {
                $myGenre[] = $genre;
            } elseif ($matchedGenre = $this->matchGenre($genre)) {
                $myGenre[] = $matchedGenre;
            }
        }

        return $myGenre;
    }

    private function matchGenre($genre)
    {
        switch ($genre) {
            case 'Sci-Fi':
                return 'Science Fiction';

                break;
        }

        return false;
    }
}
