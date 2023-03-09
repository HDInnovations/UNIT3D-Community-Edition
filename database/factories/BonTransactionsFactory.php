<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BonTransactions;

class BonTransactionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BonTransactions::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'itemID'        => \App\Models\BonExchange::factory(),
            'name'          => $this->faker->name(),
            'cost'          => $this->faker->randomFloat(),
            'sender'        => \App\Models\User::factory(),
            'receiver'      => \App\Models\User::factory(),
            'comment'       => $this->faker->text(),
            'date_actioned' => $this->faker->dateTime(),
        ];
    }
}
