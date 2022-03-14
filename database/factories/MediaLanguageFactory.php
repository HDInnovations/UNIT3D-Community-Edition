<?php

namespace Database\Factories;

use App\Models\MediaLanguage;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaLanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->word,
            'name' => $this->faker->name,
        ];
    }
}
