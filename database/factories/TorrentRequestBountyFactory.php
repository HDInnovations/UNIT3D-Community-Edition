<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\TorrentRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TorrentRequestBountyFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'     => fn () => User::factory()->create()->id,
            'seedbonus'   => $this->faker->randomFloat(),
            'requests_id' => $this->faker->randomNumber(),
            'anon'        => $this->faker->boolean(),
            'request_id'  => fn () => TorrentRequest::factory()->create()->id,
        ];
    }
}
