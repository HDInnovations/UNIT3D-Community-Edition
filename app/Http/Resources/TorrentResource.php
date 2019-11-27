<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TorrentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type'          => 'torrent',
            'id'            => (string)$this->id,
            'attributes'    => [
                'name' => $this->name,
                'release_year' => $this->release_year,
                'category' => $this->category->name,
                'type' => $this->type,
                'seeders' => $this->seeders,
                'leechers' => $this->leechers,
                'times_completed' => $this->times_completed,
                'created_at' => $this->created_at->toDayDateTimeString(),
            ],
        ];
    }
}
