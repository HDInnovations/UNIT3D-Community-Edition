<?php

declare(strict_types=1);

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'    => $this->faker->name(),
            'slug'    => $this->faker->slug(),
            'content' => $this->faker->text(),
        ];
    }
}
