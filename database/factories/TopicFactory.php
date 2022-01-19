<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Forum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'                     => $this->faker->name(),
            'slug'                     => $this->faker->slug(),
            'state'                    => $this->faker->word(),
            'pinned'                   => $this->faker->boolean(),
            'approved'                 => $this->faker->boolean(),
            'denied'                   => $this->faker->boolean(),
            'solved'                   => $this->faker->boolean(),
            'invalid'                  => $this->faker->boolean(),
            'bug'                      => $this->faker->boolean(),
            'suggestion'               => $this->faker->boolean(),
            'implemented'              => $this->faker->boolean(),
            'num_post'                 => $this->faker->randomNumber(),
            'first_post_user_id'       => fn () => User::factory()->create()->id,
            'last_post_user_id'        => $this->faker->randomNumber(),
            'first_post_user_username' => $this->faker->word(),
            'last_post_user_username'  => $this->faker->word(),
            'last_reply_at'            => $this->faker->dateTime(),
            'views'                    => $this->faker->randomNumber(),
            'forum_id'                 => fn () => Forum::factory()->create()->id,
        ];
    }
}
