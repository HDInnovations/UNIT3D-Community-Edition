<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Forum;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'  => fn () => User::factory()->create()->id,
            'forum_id' => fn () => Forum::factory()->create()->id,
            'topic_id' => fn () => Topic::factory()->create()->id,
        ];
    }
}
