<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'  => fn () => User::factory()->create()->id,
            'staff_id' => fn () => User::factory()->create()->id,
            'message'  => $this->faker->text(),
        ];
    }
}
