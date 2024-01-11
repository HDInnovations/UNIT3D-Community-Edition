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

class Network
{
    /**
     * @var array{
     *     headquarters: ?string,
     *     homepage: ?string,
     *     id: ?int,
     *     logo_path: ?string,
     *     name: ?string,
     *     origin_country: ?string,
     *     images: ?array{
     *         id: ?int,
     *         logos: ?array<
     *             int<0, max>,
     *             array{
     *                 aspect_ratio: ?float,
     *                 file_path: ?string,
     *                 height: ?int,
     *                 id: ?string,
     *                 file_type: ?string,
     *                 vote_average: ?float,
     *                 vote_count: ?int,
     *             },
     *         >,
     *     }
     * }
     */
    public mixed $data;

    public TMDB $tmdb;

    public function __construct(int $id)
    {
        $this->data = Http::acceptJson()
            ->withUrlParameters(['id' => $id])
            ->get('https://api.TheMovieDB.org/3/network/{id}', [
                'api_key'            => config('api-keys.tmdb'),
                'language'           => config('app.meta_locale'),
                'append_to_response' => 'images',
            ])
            ->json();

        $this->tmdb = new TMDB();
    }

    /**
     * @return null|array{
     *      id: ?int,
     *      headquarters: ?string,
     *      homepage: ?string,
     *      logo: ?string,
     *      name: ?string,
     *      origin_country: ?string,
     * }
     */
    public function getNetwork(): ?array
    {
        if (isset($this->data['id'], $this->data['name'])) {
            if (isset($this->data['images']['logos'][0]) && \array_key_exists('file_path', $this->data['images']['logos'][0])) {
                $logo = 'https://image.tmdb.org/t/p/original'.$this->data['images']['logos'][0]['file_path'];
            } else {
                $logo = null;
            }

            return [
                'id'             => $this->data['id'],
                'headquarters'   => $this->tmdb->ifExists('headquarters', $this->data),
                'homepage'       => $this->tmdb->ifExists('homepage', $this->data),
                'logo'           => $logo,
                'name'           => $this->data['name'],
                'origin_country' => $this->data['origin_country'] ?? null,
            ];
        }

        return null;
    }
}
