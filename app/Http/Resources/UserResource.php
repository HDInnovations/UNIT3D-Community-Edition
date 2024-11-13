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

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'username'     => $this->username,
            'group'        => $this->group->name,
            'uploaded'     => str_replace("\u{00A0}", ' ', $this->formatted_uploaded),
            'downloaded'   => str_replace("\u{00A0}", ' ', $this->formatted_downloaded),
            'ratio'        => $this->formatted_ratio,
            'buffer'       => str_replace("\u{00A0}", ' ', $this->formatted_buffer),
            'seeding'      => \count($this->seedingTorrents) ? $this->seedingTorrents : 0,
            'leeching'     => \count($this->leechingTorrents) ? $this->leechingTorrents : 0,
            'seedbonus'    => $this->seedbonus,
            'hit_and_runs' => $this->hitandruns,
        ];
    }
}
