<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\FailedLoginAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;

class FailedLoginAttemptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FailedLoginAttempt::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'    => $this->faker->randomNumber(),
            'username'   => $this->faker->userName,
            'ip_address' => $this->faker->word,
        ];
    }
}
