<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory()->create()->id,
            'title'   => $this->faker->word(),
            'imdb'    => $this->faker->word(),
            'type'    => $this->faker->word(),
            'source'  => $this->faker->word(),
        ];
    }
}
