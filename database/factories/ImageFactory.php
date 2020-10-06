<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\User;
use App\Models\Image;
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
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'album_id'    => $this->faker->randomNumber(),
            'image'       => $this->faker->word,
            'description' => $this->faker->text,
            'type'        => $this->faker->word,
            'downloads'   => $this->faker->randomNumber(),
        ];
    }
}
