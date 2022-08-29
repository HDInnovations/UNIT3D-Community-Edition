<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TwoStepAuthFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'userId'      => $this->faker->randomNumber(),
            'authCode'    => sprintf('%s%s%s%s', $this->faker->numberBetween(0, 9), $this->faker->numberBetween(0, 9), $this->faker->numberBetween(0, 9), $this->faker->numberBetween(0, 9)),
            'authCount'   => 0,
            'authStatus'  => false,
            'authDate'    => null,
            'requestDate' => Carbon::now(),
        ];
    }
}
