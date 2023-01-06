<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'    => $this->faker->name(),
            'content' => $this->faker->text(),
        ];
    }
}
