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

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Meilisearch\Client;
use Meilisearch\Contracts\MultiSearchFederation;
use Meilisearch\Contracts\SearchQuery;

class QuickSearchController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->input('query');

        $filters = [
            'deleted_at IS NULL',
            'status = 1',
            [
                'category.movie_meta = true',
                'category.tv_meta = true',
            ],
            [
                'movie.name EXISTS',
                'tv.name EXISTS',
            ]
        ];

        // Check if the query is an IMDb or TMDB ID
        $searchById = false;

        if (preg_match('/^(\d+)$/', $query, $matches)) {
            $filters[] = 'tmdb = '.$matches[1];
            $searchById = true;
        }

        if (preg_match('/tt0*(?=(\d{7,}))/', $query, $matches)) {
            $filters[] = 'imdb = '.$matches[1];
            $searchById = true;
        }

        $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));

        // Prepare the search queries
        $searchQueries = [
            (new SearchQuery())
                ->setIndexUid('torrents')
                ->setQuery($searchById ? '' : $query)
                ->setFilter($filters)
                ->setDistinct('imdb')
        ];

        // Add the people search query only if it's not an ID search
        if (!$searchById) {
            $searchQueries[] = (new SearchQuery())
                ->setIndexUid('people')
                ->setQuery($query);
            //->setFederationOptions((new FederationOptions())->setWeight(0.9));
        }

        // Perform multi-search with MultiSearchFederation
        $multiSearchResults = $client->multiSearch($searchQueries, ((new MultiSearchFederation()))->setLimit(20));

        $results = [];

        // Process the hits from the multiSearchResults
        foreach ($multiSearchResults['hits'] as $hit) {
            if ($hit['_federation']['indexUid'] === 'torrents') {
                $type = $hit['category']['movie_meta'] === true ? 'movie' : 'tv';

                $results[] = [
                    'id'    => $hit['id'],
                    'name'  => $hit[$type]['name'],
                    'year'  => $hit[$type]['year'],
                    'image' => $hit[$type]['poster'] ? tmdb_image('poster_small', $hit[$type]['poster']) : 'https://via.placeholder.com/90x135',
                    'url'   => route('torrents.similar', ['category_id' => $hit['category']['id'], 'tmdb' => $hit['tmdb']]),
                    'type'  => $type === 'movie' ? 'Movie' : 'TV Series',
                ];
            } elseif ($hit['_federation']['indexUid'] === 'people') {
                $results[] = [
                    'id'    => $hit['id'],
                    'name'  => $hit['name'],
                    'year'  => $hit['birthday'],
                    'image' => $hit['still'] ? tmdb_image('poster_small', $hit['still']) : 'https://via.placeholder.com/90x135',
                    'url'   => route('mediahub.persons.show', ['id' => $hit['id']]),
                    'type'  => 'Person',
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}
