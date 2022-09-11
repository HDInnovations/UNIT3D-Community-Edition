<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BonExchangeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'description'        => $this->faker->text(),
            'value'              => $this->faker->randomNumber(),
            'cost'               => $this->faker->randomNumber(),
            'upload'             => $this->faker->boolean(),
            'download'           => $this->faker->boolean(),
            'personal_freeleech' => $this->faker->boolean(),
            'invite'             => $this->faker->boolean(),
        ];
    }
}
