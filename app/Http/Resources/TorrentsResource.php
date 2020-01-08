<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TorrentsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => TorrentResource::collection($this->collection),
        ];
    }

    public function with($request)
    {
        return [
            'links'    => [
                'self' => route('torrents.index'),
            ],
        ];
    }
}
