<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'content'  => $this->faker->text(),
            'user_id'  => fn () => User::factory()->create()->id,
            'topic_id' => fn () => Topic::factory()->create()->id,
        ];
    }
}
