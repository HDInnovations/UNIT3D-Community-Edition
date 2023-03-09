<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bot;

class BotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'position'     => $this->faker->randomNumber(),
            'name'         => $this->faker->name(),
            'command'      => $this->faker->word(),
            'active'       => $this->faker->boolean(),
            'is_protected' => $this->faker->boolean(),
            'is_triviabot' => $this->faker->boolean(),
            'is_nerdbot'   => $this->faker->boolean(),
            'is_systembot' => $this->faker->boolean(),
            'is_casinobot' => $this->faker->boolean(),
            'is_betbot'    => $this->faker->boolean(),
            'uploaded'     => $this->faker->randomNumber(),
            'downloaded'   => $this->faker->randomNumber(),
            'fl_tokens'    => $this->faker->randomNumber(),
            'seedbonus'    => $this->faker->randomFloat(),
            'invites'      => $this->faker->randomNumber(),
        ];
    }
}
