<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type'         => $this->faker->word(),
            'email'        => $this->faker->unique()->safeEmail(),
            'referrer'     => $this->faker->text(),
            'status'       => $this->faker->boolean(),
            'moderated_at' => $this->faker->dateTime(),
            'moderated_by' => fn () => User::factory()->create()->id,
            'accepted_by'  => $this->faker->randomNumber(),
        ];
    }
}
