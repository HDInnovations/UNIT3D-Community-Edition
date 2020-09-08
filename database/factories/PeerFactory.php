<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Torrent;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Peer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'peer_id'     => $this->faker->word,
            'md5_peer_id' => $this->faker->word,
            'info_hash'   => $this->faker->word,
            'ip'          => $this->faker->word,
            'port'        => $this->faker->randomNumber(),
            'agent'       => $this->faker->word,
            'uploaded'    => $this->faker->randomNumber(),
            'downloaded'  => $this->faker->randomNumber(),
            'left'        => $this->faker->randomNumber(),
            'seeder'      => $this->faker->boolean,
            'torrent_id'  => function () {
                return Torrent::factory()->create()->id;
            },
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'torrents.id' => function () {
                return Torrent::factory()->create()->id;
            },
        ];
    }
}
