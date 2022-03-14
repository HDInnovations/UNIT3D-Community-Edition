<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type'            => $this->faker->word(),
            'notifiable_id'   => $this->faker->randomNumber(),
            'notifiable_type' => $this->faker->word(),
            'data'            => $this->faker->text(),
            'read_at'         => $this->faker->dateTime(),
        ];
    }
}
