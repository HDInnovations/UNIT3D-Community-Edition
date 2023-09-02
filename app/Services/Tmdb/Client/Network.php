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

class Network
{
    public $data;

    public function __construct($id)
    {
        $this->data = Http::acceptJson()
            ->withUrlParameters(['id' => $id])
            ->get('https://api.TheMovieDB.org/3/network/{id}', [
                'api_key'            => config('api-keys.tmdb'),
                'language'           => config('app.meta_locale'),
                'append_to_response' => 'images',
            ])
            ->json();
    }

    public function getData()
    {
        return $this->data;
    }
}
