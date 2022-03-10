<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'             => $this->faker->name(),
            'slug'             => $this->faker->slug(),
            'position'         => $this->faker->randomNumber(),
            'color'            => $this->faker->word(),
            'icon'             => $this->faker->word(),
            'effect'           => $this->faker->word(),
            'rule_id'          => null,
            'system_required'  => $this->faker->boolean(),
        ];
    }
}
