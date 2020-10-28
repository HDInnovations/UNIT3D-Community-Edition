<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Rss;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RssFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rss::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'position' => $this->faker->randomNumber(),
            'name'     => $this->faker->name,
            'user_id'  => function () {
                return User::factory()->create()->id;
            },
            'staff_id' => function () {
                return User::factory()->create()->id;
            },
            'is_private'   => $this->faker->boolean,
            'is_torrent'   => $this->faker->boolean,
            'json_torrent' => $this->faker->word,
        ];
    }
}
