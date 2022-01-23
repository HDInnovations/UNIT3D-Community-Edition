<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title'   => $this->faker->word(),
            'slug'    => $this->faker->slug(),
            'image'   => $this->faker->word(),
            'content' => $this->faker->text(),
            'user_id' => fn () => User::factory()->create()->id,
        ];
    }
}
