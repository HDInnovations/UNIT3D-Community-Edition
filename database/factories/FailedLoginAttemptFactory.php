<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FailedLoginAttemptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\FailedLoginAttempt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'user_id'    => $this->faker->randomNumber(),
        'username'   => $this->faker->userName,
        'ip_address' => $this->faker->word,
    ];
    }
}
