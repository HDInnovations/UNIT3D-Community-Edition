<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Bot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'position'     => $this->faker->randomNumber(),
            'slug'         => $this->faker->slug,
            'name'         => $this->faker->name,
            'command'      => $this->faker->word,
            'color'        => $this->faker->word,
            'icon'         => $this->faker->word,
            'emoji'        => $this->faker->word,
            'info'         => $this->faker->word,
            'about'        => $this->faker->word,
            'help'         => $this->faker->text,
            'active'       => $this->faker->boolean,
            'is_protected' => $this->faker->boolean,
            'is_triviabot' => $this->faker->boolean,
            'is_nerdbot'   => $this->faker->boolean,
            'is_systembot' => $this->faker->boolean,
            'is_casinobot' => $this->faker->boolean,
            'is_betbot'    => $this->faker->boolean,
            'uploaded'     => $this->faker->randomNumber(),
            'downloaded'   => $this->faker->randomNumber(),
            'fl_tokens'    => $this->faker->randomNumber(),
            'seedbonus'    => $this->faker->randomFloat(),
            'invites'      => $this->faker->randomNumber(),
        ];
    }
}
