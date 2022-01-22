<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

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
