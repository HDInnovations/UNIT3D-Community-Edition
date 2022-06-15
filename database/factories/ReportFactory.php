<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type'          => $this->faker->word(),
            'reporter_id'   => fn () => User::factory()->create()->id,
            'staff_id'      => fn () => User::factory()->create()->id,
            'title'         => $this->faker->word(),
            'message'       => $this->faker->text(),
            'solved'        => $this->faker->randomNumber(),
            'verdict'       => $this->faker->text(),
            'reported_user' => fn () => User::factory()->create()->id,
            'torrent_id'    => fn () => Torrent::factory()->create()->id,
            'request_id'    => fn () => TorrentRequest::factory()->create()->id,
        ];
    }
}
