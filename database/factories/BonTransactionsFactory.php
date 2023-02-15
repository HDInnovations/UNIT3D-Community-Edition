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
            'itemID'        => fn () => BonExchange::factory()->create()->id,
            'name'          => $this->faker->name(),
            'cost'          => $this->faker->randomFloat(),
            'sender'        => fn () => User::factory()->create()->id,
            'receiver'      => fn () => User::factory()->create()->id,
            'torrent_id'    => $this->faker->randomNumber(),
            'donation_id'   => $this->faker->randomNumber(),
            'post_id'       => $this->faker->randomNumber(),
            'comment'       => $this->faker->text(),
            'date_actioned' => $this->faker->dateTime(),
        ];
    }
}
