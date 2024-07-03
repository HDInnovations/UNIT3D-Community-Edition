<?php

declare(strict_types=1);

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

namespace App\Http\Livewire;

use App\Models\Movie;
use App\Models\Person;
use App\Models\Torrent;
use App\Models\Tv;
use Livewire\Component;

class QuickSearchDropdown extends Component
{
    public string $quicksearchRadio = 'movies';

    public string $quicksearchText = '';

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $search = '%'.str_replace(' ', '%', $this->quicksearchText).'%';

        $searchResults = [];

        switch ($this->quicksearchRadio) {
            case 'movies':
                $query = Movie::query()
                    ->select(['id', 'poster', 'title', 'release_date'])
                    ->selectSub(
                        Torrent::query()
                            ->select('category_id')
                            ->whereColumn('torrents.tmdb', '=', 'movies.id')
                            ->whereRelation('category', 'movie_meta', '=', true)
                            ->limit(1),
                        'category_id'
                    )
                    ->selectRaw("concat(title, ' ', release_date) as title_and_year")
                    ->when(
                        preg_match('/^\d+$/', $this->quicksearchText),
                        fn ($query) => $query->where('id', '=', $this->quicksearchText),
                        fn ($query) => $query
                            ->when(
                                preg_match('/tt0*(?=(\d{7,}))/', $this->quicksearchText, $matches),
                                fn ($query) => $query->where('imdb_id', '=', $matches[1]),
                                fn ($query) => $query->having('title_and_year', 'LIKE', $search),
                            )
                    )
                    ->havingNotNull('category_id')
                    ->oldest('title')
                    ->take(10);

                break;
            case 'series':
                $query = Tv::query()
                    ->select(['id', 'poster', 'name', 'first_air_date'])
                    ->selectSub(
                        Torrent::query()
                            ->select('category_id')
                            ->whereColumn('torrents.tmdb', '=', 'tv.id')
                            ->whereRelation('category', 'tv_meta', '=', true)
                            ->limit(1),
                        'category_id'
                    )
                    ->selectRaw("concat(name, ' ', first_air_date) as title_and_year")
                    ->when(
                        preg_match('/^\d+$/', $this->quicksearchText),
                        fn ($query) => $query->where('id', '=', $this->quicksearchText),
                        fn ($query) => $query
                            ->when(
                                preg_match('/tt0*(?=(\d{7,}))/', $this->quicksearchText, $matches),
                                fn ($query) => $query->where('imdb_id', '=', $matches[1]),
                                fn ($query) => $query->having('title_and_year', 'LIKE', $search),
                            )
                    )
                    ->havingNotNull('category_id')
                    ->oldest('name')
                    ->take(10);

                break;
            case 'persons':
                $query = Person::query()
                    ->select(['id', 'still', 'name'])
                    ->where('name', 'LIKE', $search)
                    ->oldest('name')
                    ->take(10);
        }

        if (isset($query)) {
            // 56 characters whitelisted, 3 characters long, 3 search categories, ~3000 byte response each
            // Cache should fill 56 ^ 3 * 3000 = ~526 MB
            if (preg_match("/^[a-zA-Z0-9-_ .'@:\\[\\]+&\\/,!#()?\"]{0,3}$/", $this->quicksearchText)) {
                $searchResults = cache()->remember('quicksearch:'.$this->quicksearchRadio.':'.strtolower($search), 3600 * 24, fn () => $query->get()->toArray());
            } else {
                $searchResults = $query->get()->toArray();
            }
        }

        return view('livewire.quick-search-dropdown', [
            'search_results' => $searchResults,
        ]);
    }
}
