<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TorrentRequestClaimFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\TorrentRequestClaim::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'request_id' => $this->faker->randomNumber(),
            'username'   => $this->faker->userName,
            'anon'       => $this->faker->randomNumber(),
        ];
    }
}
