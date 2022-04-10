<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ChatStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'  => $this->faker->unique()->word(),
            'color' => $this->faker->unique()->hexColor(),
            'icon'  => $this->faker->lexify(),
        ];
    }
}
