<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Forum;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'forum_id'    => fn () => Forum::factory()->create()->id,
            'group_id'    => fn () => Group::factory()->create()->id,
            'show_forum'  => $this->faker->boolean(),
            'read_topic'  => $this->faker->boolean(),
            'reply_topic' => $this->faker->boolean(),
            'start_topic' => $this->faker->boolean(),
        ];
    }
}
