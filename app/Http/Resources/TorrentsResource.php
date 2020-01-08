<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TorrentsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection[]
     */
    public function toArray($request): array
    {
        return [
            'data' => TorrentResource::collection($this->collection),
        ];
    }

    /**
     * @return string[][]
     */
    public function with($request): array
    {
        return [
            'links'    => [
                'self' => route('torrents.index'),
            ],
        ];
    }
}
