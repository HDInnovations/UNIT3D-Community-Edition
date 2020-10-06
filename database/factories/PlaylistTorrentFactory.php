<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Torrent;
use App\Models\Playlist;
use App\Models\PlaylistTorrent;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaylistTorrentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PlaylistTorrent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'position'    => $this->faker->randomNumber(),
            'playlist_id' => function () {
                return Playlist::factory()->create()->id;
            },
            'torrent_id' => function () {
                return Torrent::factory()->create()->id;
            },
            'tmdb_id' => $this->faker->randomNumber(),
        ];
    }
}
