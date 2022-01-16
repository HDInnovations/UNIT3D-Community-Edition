<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Album;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     */
    public function definition(): array
    {
        return [
            'user_id'     => fn () => User::factory()->create()->id,
            'name'        => $this->faker->name(),
            'description' => $this->faker->text(),
            'imdb'        => $this->faker->word(),
            'cover_image' => $this->faker->word(),
        ];
    }
}
