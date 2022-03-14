<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InternalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'effect' => $this->faker->word,
            'icon'   => $this->faker->word,
            'name'   => $this->faker->unique()->name,
        ];
    }
}
