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
use Meilisearch\Contracts\FederationOptions;
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
        if (preg_match('/^(\d+)$/', $query, $matches)) {
            $filters[] = 'tmdb = '.$matches[1];
        }

        if (preg_match('/tt0*(?=(\d{7,}))/', $query, $matches)) {
            $filters[] = 'imdb = '.$matches[1];
        }

        $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));

        // Perform multi-search
        $multiSearchResults = $client->multiSearch([
            (new SearchQuery())
                ->setIndexUid('torrents')
                ->setQuery($query)
                ->setFilter($filters)
                ->setDistinct('imdb'),
            (new SearchQuery())
                ->setIndexUid('people')
                ->setQuery($query)
                ->setFederationOptions((new FederationOptions())->setWeight(0.9)),
        ], ((new MultiSearchFederation()))->setLimit(20));

        $results = [];

        // Process the hits from the multiSearchResults
        foreach ($multiSearchResults['hits'] as $hit) {
            if ($hit['_federation']['indexUid'] === 'torrents') {
                if ($hit['category']['movie_meta'] === true || $hit['category']['tv_meta'] === true) {
                    $type = $hit['category']['movie_meta'] === true ? 'Movie' : 'TV Series';
                    $name = $hit['category']['movie_meta'] === true ? $hit['movie']['name'] : $hit['tv']['name'];
                    $year = $hit['category']['movie_meta'] === true ? $hit['movie']['year'] : $hit['tv']['year'];
                    $poster = $hit['category']['movie_meta'] === true ? $hit['movie']['poster'] : $hit['tv']['poster'];
                    $url = route('torrents.similar', ['category_id' => $hit['category']['id'], 'tmdb' => $hit['tmdb']]);

                    $results[] = [
                        'id'    => $hit['id'],
                        'name'  => $name,
                        'year'  => $year,
                        'image' => $poster ? tmdb_image('poster_small', $poster) : 'https://via.placeholder.com/90x135',
                        'url'   => $url,
                        'type'  => $type,
                    ];
                }
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
