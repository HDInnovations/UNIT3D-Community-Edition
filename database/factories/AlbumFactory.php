<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Album;

class AlbumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Album::class;

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
            'imdb' => $this->faker->word,
            'name' => $this->faker->name,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
