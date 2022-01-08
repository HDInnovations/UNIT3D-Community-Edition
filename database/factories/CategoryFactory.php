<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->name,
            'slug'        => $this->faker->slug,
            'image'       => $this->faker->word,
            'position'    => $this->faker->randomNumber(),
            'icon'        => $this->faker->word,
            'no_meta'     => true,
            'music_meta'  => false,
            'game_meta'   => false,
            'tv_meta'     => false,
            'movie_meta'  => false,
            'num_torrent' => $this->faker->randomNumber(),
        ];
    }
}
