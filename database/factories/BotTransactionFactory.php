<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Bot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BotTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type'    => $this->faker->word(),
            'cost'    => $this->faker->randomFloat(),
            'user_id' => fn () => User::factory()->create()->id,
            'bot_id'  => fn () => Bot::factory()->create()->id,
            'to_user' => $this->faker->boolean(),
            'to_bot'  => $this->faker->boolean(),
            'comment' => $this->faker->text(),
        ];
    }
}
