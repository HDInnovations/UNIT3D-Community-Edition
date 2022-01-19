<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RssFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'position'     => $this->faker->randomNumber(),
            'name'         => $this->faker->name(),
            'user_id'      => fn () => User::factory()->create()->id,
            'staff_id'     => fn () => User::factory()->create()->id,
            'is_private'   => $this->faker->boolean(),
            'is_torrent'   => $this->faker->boolean(),
            'json_torrent' => $this->faker->word(),
        ];
    }
}
