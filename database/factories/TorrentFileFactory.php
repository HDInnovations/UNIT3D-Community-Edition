<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Torrent;
use Illuminate\Database\Eloquent\Factories\Factory;

class TorrentFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\TorrentFile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'       => $this->faker->name,
            'size'       => $this->faker->randomNumber(),
            'torrent_id' => function () {
                return Torrent::factory()->create()->id;
            },
        ];
    }
}
