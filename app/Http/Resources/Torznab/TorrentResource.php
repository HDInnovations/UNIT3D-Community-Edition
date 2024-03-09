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

namespace App\Http\Resources\Torznab;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Torrent
 */
class TorrentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'title'    => $this->name,
            'guid'     => route('torrents.show', ['id' => $this->id]),
            'comments' => route('torrents.show', ['id' => $this->id]).'#comments',
            'pubDate'  => $this->created_at->toRssString(),
            'size'     => $this->size,
            'files'    => $this->num_file,
            'grabs'    => $this->times_completed,
            'link'     => route('torrents.show', ['id' => $this->id]),
            'category' => [
            ],
            'description' => $this->description,
            'enclosure'   => [
                '@attributes' => [
                    'url'    => route('torrent.download.rsskey', ['id' => $this->id, 'rsskey' => $request->user()->rsskey]),
                    'length' => $this->size,
                    'type'   => 'application/x-bittorrent',
                ],
            ],
            $this->mergeWhen($request->boolean('extended'), [
                'torznab:size' => $this->size,
                'torznab:tag'  => [
                    $this->when($this->doubleup, fn () => 'doubleup'),
                    $this->when($this->refundable, fn () => 'refundable'),
                    $this->when((bool) $this->highspeed, fn () => 'highspeed'),
                    $this->when((bool) $this->anon, fn () => 'anon'),
                    $this->when($this->sticky, fn () => 'sticky'),
                    $this->when((bool) $this->sd, fn () => 'sd'),
                    $this->when((bool) $this->internal, fn () => 'internal'),
                ],
                'torznab:poster'               => $this->anon ? __('common.anonymous') : $this->when($this->relationLoaded('user'), $this->user->username),
                'torznab:seeders'              => $this->seeders,
                'torznab:leechers'             => $this->leechers,
                'torznab:peers'                => $this->seeders + $this->leechers,
                'torznab:files'                => $this->num_file,
                'torznab:grabs'                => $this->times_completed,
                'torznab:infohash'             => bin2hex($this->info_hash),
                'torznab:seedtype'             => 'seedtime',
                'torznab:minimumseedtime'      => config('hitrun.seedtime'),
                'torznab:downloadvolumefactor' => match (true) {
                    cache()->get('freeleech_token:'.$request->user()->id.':'.$this->id) => 0,
                    $this->fl_until === null && $this->free > 0                         => (100 - min(100, $this->free)) / 100,
                    default                                                             => 1,
                },
                'torznab:uploadvolumefactor' => match (true) {
                    $this->du_until === null && $this->doubleup => 2,
                    default                                     => 1,
                },
                'torznab:category' => [
                ],
                'torznab:imdbid'   => 'tt'.$this->imdb,
                'torznab:tmdbid'   => $this->tmdb,
                'torznab:tvdbid'   => $this->tvdb,
                'torznab:season'   => $this->season_number,
                'torznab:episode'  => $this->episode_number,
                'torznab:comments' => $this->whenCounted('comments'),
                'torznab:year'     => $this->release_year,
                'torznab:type'     => $this->when($this->relationLoaded('type'), $this->type->name),
            ]),
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     */
    public function withResponse($request, $response): void
    {
        $response->setEncodingOptions(JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
