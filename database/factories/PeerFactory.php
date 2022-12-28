<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Torrent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeerFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'peer_id'     => $this->faker->asciify('-qB4450-************'),
            'ip'          => \inet_pton($this->faker->ipv4()),
            'port'        => $this->faker->numberBetween(0, 65535),
            'agent'       => $this->faker->word(),
            'uploaded'    => $this->faker->randomNumber(),
            'downloaded'  => $this->faker->randomNumber(),
            'left'        => $this->faker->randomNumber(),
            'seeder'      => $this->faker->boolean(),
            'torrent_id'  => fn () => Torrent::factory()->create()->id,
            'user_id'     => fn () => User::factory()->create()->id,
        ];
    }
}
