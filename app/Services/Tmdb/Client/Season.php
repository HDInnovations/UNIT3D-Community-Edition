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
use Illuminate\Support\Str;

class Season
{
    /**
     * @var array{
     *     _id: ?string,
     *     air_date: ?string,
     *     episodes: ?array<
     *         int<0, max>,
     *         array{
     *             air_date: ?string,
     *             episode_number: ?int,
     *             id: ?int,
     *             name: ?string,
     *             overview: ?string,
     *             production_code: ?string,
     *             runtime: ?int,
     *             season_number: ?int,
     *             show_id: ?int,
     *             still_path: ?string,
     *             vote_average: ?float,
     *             vote_count: ?int,
     *             crew: ?array<
     *                 int<0, max>,
     *                 array{
     *                     department: ?string,
     *                     job: ?string,
     *                     credit_id: ?string,
     *                     adult: ?bool,
     *                     gender: ?int,
     *                     id: ?int,
     *                     known_for_department: ?string,
     *                     name: ?string,
     *                     original_name: ?string,
     *                     popularity: ?float,
     *                     profile_path: ?string,
     *                 },
     *             >,
     *             guest_stars: ?array<
     *                 int<0, max>,
     *                 array{
     *                     character: ?string,
     *                     credit_id: ?string,
     *                     order: ?string,
     *                     adult: ?bool,
     *                     gender: ?int,
     *                     id: ?int,
     *                     known_for_department: ?string,
     *                     name: ?string,
     *                     original_name: ?string,
     *                     popularity: ?float,
     *                     profile_path: ?string,
     *                 },
     *             >,
     *         },
     *     >,
     *     name: ?string,
     *     overview: ?string,
     *     id: ?int,
     *     poster_path: ?string,
     *     season_number: ?int,
     *     vote_average: ?float,
     * }
     */
    public array $data;

    public TMDB $tmdb;

    public function __construct(public int $tvId, public int $seasonNumber)
    {
        $this->data = Http::acceptJson()
            ->withUrlParameters(['tvId' => $tvId, 'seasonNumber' => $seasonNumber])
            ->get('https://api.TheMovieDB.org/3/tv/{tvId}/season/{seasonNumber}', [
                'api_key'            => config('api-keys.tmdb'),
                'language'           => config('app.meta_locale'),
                'append_to_response' => 'videos,images,credits,external_ids',
            ])
            ->json();

        $this->tmdb = new TMDB();
    }

    /**
     * @return array{
     *     id: ?int,
     *     air_date: ?string,
     *     poster: ?string,
     *     name: ?string,
     *     overview: ?string,
     *     season_number: ?int,
     *     tv_id: int,
     * }
     */
    public function getSeason(): array
    {
        return [
            'id'            => $this->data['id'] ?? null,
            'air_date'      => $this->data['air_date'] ?? null,
            'poster'        => $this->tmdb->image('poster', $this->data),
            'name'          => $this->data['name'] ?? null,
            'overview'      => $this->data['overview'] ?? null,
            'season_number' => $this->data['season_number'] ?? null,
            'tv_id'         => $this->tvId,
        ];
    }

    /**
     * @return array<
     *     int<0, max>,
     *     array{
     *         id: ?int,
     *         tv_id: ?int,
     *         air_date: ?string,
     *         name: ?string,
     *         episode_number: ?int,
     *         overview: ?string,
     *         still: ?string,
     *         production_code: ?string,
     *         season_number: ?int,
     *         vote_average: ?float,
     *         vote_count: ?int,
     *         season_id: ?int,
     *     }
     * >
     */
    public function getEpisodes(): array
    {
        $episodes = [];

        foreach ($this->data['episodes'] as $episode) {
            $episodes[] = [
                'id'              => $episode['id'] ?? null,
                'tv_id'           => $this->tvId ?? null,
                'air_date'        => $this->tmdb->ifExists('air_date', $episode),
                'name'            => Str::limit($this->tmdb->ifExists('name', $episode), 200),
                'episode_number'  => $episode['episode_number'] ?? null,
                'overview'        => $this->tmdb->ifExists('overview', $episode),
                'still'           => $this->tmdb->image('still', $episode),
                'production_code' => $episode['production_code'] ?? null,
                'season_number'   => $episode['season_number'] ?? null,
                'vote_average'    => $episode['vote_average'] ?? null,
                'vote_count'      => $episode['vote_count'] ?? null,
                'season_id'       => $this->data['id'] ?? null,
            ];
        }

        return $episodes;
    }
}
