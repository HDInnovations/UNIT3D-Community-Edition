<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FreeleechTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'    => $this->faker->randomNumber(),
            'torrent_id' => $this->faker->randomNumber(),
        ];
    }
}
