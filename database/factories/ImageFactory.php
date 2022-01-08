<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'     => fn () => User::factory()->create()->id,
            'album_id'    => $this->faker->randomNumber(),
            'image'       => $this->faker->word,
            'description' => $this->faker->text,
            'type'        => $this->faker->word,
            'downloads'   => $this->faker->randomNumber(),
        ];
    }
}
