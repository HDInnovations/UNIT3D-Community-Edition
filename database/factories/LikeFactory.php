<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'     => fn () => User::factory()->create()->id,
            'post_id'     => fn () => Post::factory()->create()->id,
            'subtitle_id' => $this->faker->randomNumber(),
            'like'        => $this->faker->boolean(),
            'dislike'     => $this->faker->boolean(),
        ];
    }
}
