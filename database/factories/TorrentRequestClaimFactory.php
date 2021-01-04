<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\TorrentRequestClaim;
use Illuminate\Database\Eloquent\Factories\Factory;

class TorrentRequestClaimFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TorrentRequestClaim::class;

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
