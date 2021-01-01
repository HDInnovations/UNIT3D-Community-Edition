<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Playlist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaylistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Playlist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'name'        => $this->faker->name,
            'description' => $this->faker->text,
            'cover_image' => $this->faker->word,
            'position'    => $this->faker->randomNumber(),
            'is_private'  => $this->faker->boolean,
            'is_pinned'   => $this->faker->boolean,
            'is_featured' => $this->faker->boolean,
        ];
    }
}
