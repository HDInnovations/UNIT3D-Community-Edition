<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TorrentRequestClaimFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'request_id' => $this->faker->randomNumber(),
            'username'   => $this->faker->userName(),
            'anon'       => $this->faker->randomNumber(),
        ];
    }
}
