<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\BonExchange;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BonTransactionsFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bon_exchange_id' => fn () => BonExchange::factory()->create()->id,
            'name'            => $this->faker->name(),
            'cost'            => $this->faker->randomFloat(),
            'sender_id'       => fn () => User::factory()->create()->id,
            'receiver_id'     => fn () => User::factory()->create()->id,
            'torrent_id'      => $this->faker->randomNumber(),
            'donation_id'     => $this->faker->randomNumber(),
            'post_id'         => $this->faker->randomNumber(),
            'comment'         => $this->faker->text(),
            'created_at'      => $this->faker->dateTime(),
        ];
    }
}
