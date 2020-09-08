<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Bot;
use Illuminate\Database\Eloquent\Factories\Factory;

class BotTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\BotTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type'    => $this->faker->word,
            'cost'    => $this->faker->randomFloat(),
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'bot_id' => function () {
                return Bot::factory()->create()->id;
            },
            'to_user' => $this->faker->boolean,
            'to_bot'  => $this->faker->boolean,
            'comment' => $this->faker->text,
        ];
    }
}
