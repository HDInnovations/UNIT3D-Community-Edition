<?php

namespace App\Http\Resources\Torznab;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CapabilitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'server' => [
                '@attributes' => [
                    'version'   => '1.3',
                    'title'     => config('other.title'),
                    'strapline' => config('other.subTitle'),
                    'url'       => config('app.url'),
                ],
            ],
            'limits' => [
                '@attributes' => [
                    'max'     => 100,
                    'default' => 100,
                ],
            ],
            'registration' => [
                '@attributes' => [
                    'available' => 'no',
                    'open'      => 'no',
                ],
            ],
            'searching' => [
                'search' => [
                    '@attributes' => [
                        'available'       => 'yes',
                        'supportedParams' => 'q,imdbid,tvdbid,tmdbid,tag',
                    ],
                ],
                'tv-search' => $this->when(Category::query()->where('tv_meta', '=', true)->exists(), [
                    '@attributes' => [
                        'available'       => 'yes',
                        'supportedParams' => 'q,season,ep,imdbid,tvdbid,tmdbid,tag',
                    ],
                ]),
                'movie-search' => $this->when(Category::query()->where('movie_meta', '=', true)->exists(), [
                    '@attributes' => [
                        'available'       => 'yes',
                        'supportedParams' => 'q,imdbid,tmdbid,tag',
                    ],
                ]),
            ],
            'categories' => [
                'category' => CategoryResource::collection(Category::all()),
            ],
            'tags' => [
                'tag' => [
                    [
                        'name'        => 'anon',
                        'description' => 'This torrent was uploaded by an anonymous user.',
                    ],
                    [
                        'name'        => 'featured',
                        'description' => 'This torrent is featured by staff and has both 100% freeleech and double upload.',
                    ],
                    [
                        'name'        => 'highspeed',
                        'description' => 'An IP of a registered seedbox is in this torrent\'s swarm',
                    ],
                    [
                        'name'        => 'internal',
                        'description' => 'This torrent is an internal release.',
                    ],
                    [
                        'name'        => 'personal_release',
                        'description' => 'The content of this torrent was created by the uploader.',
                    ],
                    [
                        'name'        => 'refundable',
                        'description' => 'You are refunded downloaded credit if you continue seeding this torrent.',
                    ],
                    [
                        'name'        => 'sd',
                        'description' => 'This torrent contains standard-definition content.',
                    ],
                    [
                        'name'        => 'sticky',
                        'description' => 'This torrent is pinned to the top of the torrent list.',
                    ],
                    [
                        'name'        => 'stream',
                        'description' => 'This torrent is optimized for streaming remotely.',
                    ],
                ]
            ]
        ];
    }
}
