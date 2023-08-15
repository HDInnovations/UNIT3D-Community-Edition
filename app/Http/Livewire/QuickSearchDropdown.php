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

use App\Models\Person;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class QuickSearchDropdown extends Component
{
    public string $quicksearchRadio = 'movies';

    public string $quicksearchText = '';

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $search = '%'.str_replace(' ', '%', $this->quicksearchText).'%';

        $idSearch = '';

        if (preg_match('/^(\d+)$/', $this->quicksearchText, $matches)) {
            $idSearch = ' AND tmdb = '.$matches[1];
        }

        if (preg_match('/tt0*(?=(\d{7,}))/', $this->quicksearchText, $matches)) {
            $idSearch = ' AND imdb = '.$matches[1];
        }

        $searchResults = [];

        switch ($this->quicksearchRadio) {
            case 'movies':
                $query = [
                    'hitsPerPage' => 20,
                    'page'        => 1,
                    'distinct'    => 'movie.id',
                    'filter'      => 'deleted_at IS NULL AND category.movie_meta = 1 AND movie.name IS NOT NULL'.$idSearch,
                ];

                if ($idSearch === '') {
                    $query['q'] = json_encode($this->quicksearchText);
                }

                $results = Http::acceptJson()
                    ->withToken(config('meilisearch.key'))
                    ->post(config('meilisearch.host').'/indexes/torrents/search', $query)
                    ->json();

                foreach ($results['hits'] ?? [] as $hit) {
                    $searchResults[] = [
                        'id'          => $hit['tmdb'],
                        'poster'      => isset($hit['movie']['poster']) ? tmdb_image('poster_small', $hit['movie']['poster']) : null,
                        'title'       => $hit['movie']['name'] ?? '',
                        'year'        => $hit['movie']['year'] ?? 0,
                        'category_id' => $hit['category']['id'] ?? 0,
                    ];
                }

                break;
            case 'series':
                $query = [
                    'hitsPerPage' => 20,
                    'page'        => 1,
                    'distinct'    => 'tv.id',
                    'filter'      => 'deleted_at IS NULL AND category.tv_meta = 1 AND tv.name IS NOT NULL'.$idSearch,
                ];

                if ($idSearch === '') {
                    $query['q'] = json_encode($this->quicksearchText);
                }

                $results = Http::acceptJson()
                    ->withToken(config('meilisearch.key'))
                    ->post(config('meilisearch.host').'/indexes/torrents/search', $query)
                    ->json();

                foreach ($results['hits'] ?? [] as $hit) {
                    $searchResults[] = [
                        'id'          => $hit['tmdb'],
                        'poster'      => isset($hit['tv']['poster']) ? tmdb_image('poster_small', $hit['tv']['poster']) : null,
                        'name'        => $hit['tv']['name'] ?? '',
                        'year'        => $hit['tv']['year'] ?? 0,
                        'category_id' => $hit['category']['id'] ?? 0,
                    ];
                }

                break;
            case 'persons':
                $searchResults = Person::query()
                    ->select(['id', 'still', 'name'])
                    ->where('name', 'LIKE', $search)
                    ->oldest('name')
                    ->take(10)
                    ->get()
                    ->toArray();
        }

        // if (isset($query)) {
        //     // 56 characters whitelisted, 3 characters long, 3 search categories, ~3000 byte response each
        //     // Cache should fill 56 ^ 3 * 3000 = ~526 MB
        //     if (preg_match("/^[a-zA-Z0-9-_ .'@:\\[\\]+&\\/,!#()?\"]{0,3}$/", $this->quicksearchText)) {
        //         $searchResults = cache()->remember('quicksearch:'.$this->quicksearchRadio.':'.strtolower($search), 3600 * 24, fn () => $query->get()->toArray());
        //     } else {
        //         $searchResults = $query->get()->toArray();
        //     }
        // }

        return view('livewire.quick-search-dropdown', [
            'search_results' => $searchResults,
        ]);
    }
}
