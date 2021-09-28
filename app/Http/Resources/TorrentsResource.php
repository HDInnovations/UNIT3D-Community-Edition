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

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TorrentsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array<string, \Illuminate\Http\Resources\Json\AnonymousResourceCollection>
     */
    public function toArray($request): array
    {
        return [
            'data' => TorrentResource::collection($this->collection),
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function with($request): array
    {
        return [
            'links'    => [
                'self' => \route('torrents.index'),
            ],
        ];
    }
}
