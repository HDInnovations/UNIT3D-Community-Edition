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
use App\Models\Torrent;
use Illuminate\Http\Request;
use Meilisearch\Client;
use Meilisearch\Endpoints\Indexes;

class QuickSearchController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->input('query');

        $filters = [
            'deleted_at IS NULL',
            'status = 1',
        ];

        $results = [];

        // Search for movies
        $movieResults = Torrent::search($query, function (Indexes $meilisearch, string $query, array $options) use ($filters) {
            $filters[] = 'category.movie_meta = true';
            $filters[] = 'movie.name EXISTS';
            $options['filter'] = $filters;
            $options['distinct'] = 'movie.id';
            $options['showRankingScore'] = true;

            return $meilisearch->search($query, $options);
        })->get();

        foreach ($movieResults as $result) {
            $results[] = [
                'id'    => $result->id,
                'name'  => $result->movie->title,
                'year'  => $result->movie->release_date,
                'image' => $result->movie->poster ? tmdb_image('poster_small', $result->movie->poster) : 'https://via.placeholder.com/90x135',
                'url'   => route('torrents.similar', ['category_id' => $result->category->id, 'tmdb' => $result->tmdb]),
                'type'  => 'Movie',
                'score' => $result->_rankingInfo['score'] ?? 0,
            ];
        }

        // Search for TV shows
        $tvResults = Torrent::search($query, function (Indexes $meilisearch, string $query, array $options) use ($filters) {
            $filters[] = 'category.tv_meta = true';
            $filters[] = 'tv.name EXISTS';
            $options['filter'] = $filters;
            $options['distinct'] = 'tv.id';
            $options['showRankingScore'] = true;

            return $meilisearch->search($query, $options);
        })->get();

        foreach ($tvResults as $result) {
            $results[] = [
                'id'    => $result->id,
                'name'  => $result->tv->name,
                'year'  => $result->tv->first_air_date,
                'image' => $result->tv->poster ? tmdb_image('poster_small', $result->tv->poster) : 'https://via.placeholder.com/90x135',
                'url'   => route('torrents.similar', ['category_id' => $result->category->id, 'tmdb' => $result->tmdb]),
                'type'  => 'TV Series',
                'score' => $result->_rankingInfo['score'] ?? 0,
            ];
        }

        // Search for persons
        $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));
        $index = $client->index('people');
        $personResults = $index->search($query, [
            'showRankingScore' => true,
        ]);

        foreach ($personResults->getHits() as $result) {
            $results[] = [
                'id'    => $result['id'],
                'name'  => $result['name'],
                'year'  => $result['birthday'],
                'image' => $result['still'] ? tmdb_image('poster_small', $result['still']) : 'https://via.placeholder.com/90x135',
                'url'   => route('mediahub.persons.show', ['id' => $result['id']]),
                'type'  => 'Person',
                'score' => $result['_rankingInfo']['score'] ?? 0,
            ];
        }

        // Sort results by score
        usort($results, fn ($a, $b) => $b['score'] <=> $a['score']);

        return response()->json(['results' => $results]);
    }
}
