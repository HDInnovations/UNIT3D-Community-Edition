<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\TorrentRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Report::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'type'        => $this->faker->word,
        'reporter_id' => function () {
            return User::factory()->create()->id;
        },
        'staff_id' => function () {
            return User::factory()->create()->id;
        },
        'title'         => $this->faker->word,
        'message'       => $this->faker->text,
        'solved'        => $this->faker->randomNumber(),
        'verdict'       => $this->faker->text,
        'reported_user' => function () {
            return User::factory()->create()->id;
        },
        'torrent_id' => function () {
            return Torrent::factory()->create()->id;
        },
        'request_id' => function () {
            return TorrentRequest::factory()->create()->id;
        },
    ];
    }
}
