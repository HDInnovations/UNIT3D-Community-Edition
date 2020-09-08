<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BonTransactionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\BonTransactions::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'itemID' => function () {
            return BonExchange::factory()->create()->id;
        },
        'name'   => $this->faker->name,
        'cost'   => $this->faker->randomFloat(),
        'sender' => function () {
            return User::factory()->create()->id;
        },
        'receiver' => function () {
            return User::factory()->create()->id;
        },
        'torrent_id'    => $this->faker->randomNumber(),
        'donation_id'   => $this->faker->randomNumber(),
        'post_id'       => $this->faker->randomNumber(),
        'comment'       => $this->faker->text,
        'date_actioned' => $this->faker->dateTime(),
    ];
    }
}
