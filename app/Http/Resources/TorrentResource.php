<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TorrentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type'          => 'torrent',
            'id'            => (string) $this->id,
            'attributes'    => [
                'name' => $this->name,
                'release_year' => $this->release_year,
                'category' => $this->category->name,
                'type' => $this->type,
                'seeders' => $this->seeders,
                'leechers' => $this->leechers,
                'times_completed' => $this->times_completed,
                'tmdb_id' => $this->tmdb,
                'imdb_id' => $this->imdb,
                'tvdb_id' => $this->tvdb,
                'mal_id' => $this->mal,
                'igdb_id' => $this->igdb,
                'created_at' => $this->created_at->toDayDateTimeString(),
                'download_link' => route('torrent.download.rsskey', ['id' => $this->id, 'rsskey' => auth('api')->user()->rsskey]),
            ],
        ];
    }
}
