<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrivateMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sender_id'   => fn () => User::factory()->create()->id,
            'receiver_id' => fn () => User::factory()->create()->id,
            'subject'     => $this->faker->word(),
            'message'     => $this->faker->text(),
            'read'        => $this->faker->boolean(),
            'related_to'  => $this->faker->randomNumber(),
        ];
    }
}
