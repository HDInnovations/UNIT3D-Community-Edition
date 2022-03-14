<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cover_image' => $this->faker->word,
            'description' => $this->faker->text,
            'imdb'        => $this->faker->word,
            'name'        => $this->faker->name,
            'user_id'     => \App\Models\User::factory(),
        ];
    }
}
