<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ForumFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'position'                => $this->faker->randomNumber(),
            'num_topic'               => $this->faker->randomNumber(),
            'num_post'                => $this->faker->randomNumber(),
            'last_topic_id'           => $this->faker->randomNumber(),
            'last_topic_name'         => $this->faker->word(),
            'last_post_user_id'       => $this->faker->randomNumber(),
            'last_post_user_username' => $this->faker->word(),
            'name'                    => $this->faker->name(),
            'slug'                    => $this->faker->slug(),
            'description'             => $this->faker->text(),
            'parent_id'               => $this->faker->randomNumber(),
        ];
    }
}
