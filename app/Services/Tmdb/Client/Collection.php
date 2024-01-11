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

namespace App\Services\Tmdb\Client;

use App\Services\Tmdb\TMDB;
use Illuminate\Support\Facades\Http;

class Collection
{
    /**
     * @var array{
     *     id: ?int,
     *     name: ?string,
     *     overview: ?string,
     *     poster_path: ?string,
     *     backdrop_path: ?string,
     *     parts: ?array<
     *         int<0, max>,
     *         array{
     *             adult: ?boolean,
     *             backdrop_path: ?string,
     *             id: ?int,
     *             title: ?string,
     *             original_language: ?string,
     *             original_title: ?string,
     *             overview: ?string,
     *             poster_path: ?string,
     *             media_type: ?string,
     *             genre_ids: array<int>,
     *             popularity: ?float,
     *             release_date: ?string,
     *             video: ?boolean,
     *             vote_average: ?float,
     *             vote_count: ?int,
     *         },
     *     >,
     * }
     */
    public mixed $data;

    public TMDB $tmdb;

    public function __construct(int $id)
    {
        $this->data = Http::acceptJson()
            ->withUrlParameters(['id' => $id])
            ->get('https://api.TheMovieDB.org/3/collection/{id}', [
                'api_key'            => config('api-keys.tmdb'),
                'language'           => config('app.meta_locale'),
                'append_to_response' => 'videos,images,credits',
            ])
            ->json();

        $this->tmdb = new TMDB();
    }

    /**
     * @return array{
     *     id: ?int,
     *     name: ?string,
     *     name_sort: string,
     *     parts: int,
     *     overview: ?string,
     *     poster: ?string,
     *     backdrop: ?string,
     * }
     */
    public function getCollection(): array
    {
        return [
            'id'        => $this->data['id'] ?? null,
            'name'      => $this->data['name'] ?? null,
            'name_sort' => addslashes(str_replace(['The ', 'An ', 'A ', '"'], [''], $this->data['name'])),
            'parts'     => is_countable($this->data['parts']) ? \count($this->data['parts']) : 0,
            'overview'  => $this->data['overview'] ?? null,
            'poster'    => $this->tmdb->image('poster', $this->data),
            'backdrop'  => $this->tmdb->image('backdrop', $this->data),
        ];
    }
}
