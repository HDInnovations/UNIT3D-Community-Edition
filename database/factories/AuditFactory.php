<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'        => fn () => User::factory()->create()->id,
            'model_name'     => $this->faker->word(),
            'model_entry_id' => $this->faker->randomNumber(),
            'action'         => $this->faker->word(),
            'record'         => $this->faker->word(),
        ];
    }
}
