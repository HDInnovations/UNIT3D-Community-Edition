<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
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
            'level'            => $this->faker->randomNumber(),
            'color'            => $this->faker->word(),
            'icon'             => $this->faker->word(),
            'effect'           => $this->faker->word(),
            'is_internal'      => $this->faker->boolean(),
            'is_owner'         => $this->faker->boolean(),
            'is_admin'         => $this->faker->boolean(),
            'is_modo'          => $this->faker->boolean(),
            'is_trusted'       => $this->faker->boolean(),
            'is_immune'        => $this->faker->boolean(),
            'is_freeleech'     => $this->faker->boolean(),
            'is_double_upload' => $this->faker->boolean(),
            'can_upload'       => $this->faker->boolean(),
            'is_incognito'     => $this->faker->boolean(),
            'autogroup'        => $this->faker->boolean(),
        ];
    }
}
