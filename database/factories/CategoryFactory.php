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
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'        => $this->faker->name,
            'slug'        => $this->faker->slug,
            'image'       => $this->faker->word,
            'position'    => $this->faker->randomNumber(),
            'icon'        => $this->faker->word,
            'no_meta'     => $this->faker->boolean,
            'music_meta'  => $this->faker->boolean,
            'game_meta'   => $this->faker->boolean,
            'tv_meta'     => $this->faker->boolean,
            'movie_meta'  => $this->faker->boolean,
            'num_torrent' => $this->faker->randomNumber(),
        ];
    }
}
