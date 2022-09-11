<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Torrent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'           => fn () => User::factory()->create()->id,
            'agent'             => $this->faker->word(),
            'info_hash'         => fn () => Torrent::factory()->create()->id,
            'uploaded'          => $this->faker->randomNumber(),
            'actual_uploaded'   => $this->faker->randomNumber(),
            'client_uploaded'   => $this->faker->randomNumber(),
            'downloaded'        => $this->faker->randomNumber(),
            'actual_downloaded' => $this->faker->randomNumber(),
            'client_downloaded' => $this->faker->randomNumber(),
            'seeder'            => $this->faker->boolean(),
            'active'            => $this->faker->boolean(),
            'seedtime'          => $this->faker->randomNumber(),
            'immune'            => $this->faker->boolean(),
            'hitrun'            => $this->faker->boolean(),
            'prewarn'           => $this->faker->boolean(),
            'completed_at'      => $this->faker->dateTime(),
            'deleted_at'        => $this->faker->dateTime(),
        ];
    }
}
