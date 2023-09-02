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

use Illuminate\Support\Facades\Http;

class Season
{
    /** @var array<mixed>|mixed */
    public mixed $data;

    public function __construct(int $id, int $seasonId)
    {
        $this->data = Http::acceptJson()
            ->withUrlParameters(['tvId' => $id, 'seasonId' => $seasonId])
            ->get('https://api.TheMovieDB.org/3/tv/{tvId}/{seasonId}', [
                'api_key'            => config('api-keys.tmdb'),
                'language'           => config('app.meta_locale'),
                'append_to_response' => 'videos,images,credits,external_ids',
            ])
            ->json();
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
