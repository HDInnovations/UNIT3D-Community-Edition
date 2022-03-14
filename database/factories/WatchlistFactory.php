<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WatchlistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'message'  => $this->faker->text,
            'staff_id' => \App\Models\User::factory(),
            'user_id'  => \App\Models\User::factory(),
        ];
    }
}
