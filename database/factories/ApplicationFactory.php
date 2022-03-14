<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'accepted_by'  => $this->faker->randomNumber,
            'email'        => $this->faker->unique()->email,
            'moderated_at' => $this->faker->dateTime,
            'moderated_by' => \App\Models\User::factory(),
            'referrer'     => $this->faker->text,
            'status'       => $this->faker->boolean,
            'type'         => $this->faker->word,
            'user_id'      => \App\Models\User::factory(),
        ];
    }
}
