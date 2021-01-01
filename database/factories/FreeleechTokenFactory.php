<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\FreeleechToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class FreeleechTokenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FreeleechToken::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'    => $this->faker->randomNumber(),
            'torrent_id' => $this->faker->randomNumber(),
        ];
    }
}
