<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BotTransaction;

class BotTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cost'    => $this->faker->randomFloat(),
            'user_id' => \App\Models\User::factory(),
            'bot_id'  => \App\Models\Bot::factory(),
            'to_user' => $this->faker->boolean(),
            'to_bot'  => $this->faker->boolean(),
            'comment' => $this->faker->text(),
        ];
    }
}
