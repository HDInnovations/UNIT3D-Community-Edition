<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PollFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'         => fn () => User::factory()->create()->id,
            'title'           => $this->faker->word(),
            'slug'            => $this->faker->slug(),
            'ip_checking'     => $this->faker->boolean(),
            'multiple_choice' => $this->faker->boolean(),
        ];
    }
}
