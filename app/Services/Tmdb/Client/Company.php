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

class Company
{
    /**
     * @var array{
     *     description: ?string,
     *     headquarters: ?string,
     *     homepage: ?string,
     *     id: ?int,
     *     logo_path: ?string,
     *     name: ?string,
     *     origin_country: ?string,
     *     parent_company: ?string,
     * }
     */
    public mixed $data;

    public TMDB $tmdb;

    public function __construct(int $id)
    {
        $this->tmdb = new TMDB();

        $this->data = Http::acceptJson()
            ->withUrlParameters(['id' => $id])
            ->get('https://api.TheMovieDB.org/3/company/{id}', [
                'api_key'            => config('api-keys.tmdb'),
                'language'           => config('app.meta_locale'),
                'append_to_response' => 'movies,videos,images,credits',
            ])
            ->json();
    }

    /**
     * @return array{
     *      id: ?int,
     *      description: ?string,
     *      headquarters: ?string,
     *      homepage: ?string,
     *      logo: ?string,
     *      name: ?string,
     *      origin_country: ?string,
     * }
     */
    public function getCompany(): array
    {
        return [
            'id'             => $this->data['id'] ?? null,
            'description'    => $this->data['description'] ?? null,
            'headquarters'   => $this->data['headquarters'] ?? null,
            'homepage'       => $this->data['homepage'] ?? null,
            'logo'           => $this->tmdb->image('logo', $this->data),
            'name'           => $this->data['name'] ?? null,
            'origin_country' => $this->data['origin_country'] ?? null,
        ];
    }
}
