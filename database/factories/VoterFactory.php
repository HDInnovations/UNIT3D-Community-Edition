<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoterFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'poll_id'    => fn () => Poll::factory()->create()->id,
            'user_id'    => fn () => User::factory()->create()->id,
            'ip_address' => $this->faker->word(),
        ];
    }
}
