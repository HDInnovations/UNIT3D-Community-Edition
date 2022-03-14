<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PrivilegeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description'     => $this->faker->word,
            'name'            => $this->faker->name,
            'position'        => $this->faker->randomNumber,
            'slug'            => $this->faker->unique()->slug,
            'system_required' => $this->faker->boolean,
        ];
    }
}
