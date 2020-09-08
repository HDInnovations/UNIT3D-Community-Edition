<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Torrent;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\History::class;

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
            'agent'     => $this->faker->word,
            'info_hash' => function () {
                return Torrent::factory()->create()->id;
            },
            'uploaded'          => $this->faker->randomNumber(),
            'actual_uploaded'   => $this->faker->randomNumber(),
            'client_uploaded'   => $this->faker->randomNumber(),
            'downloaded'        => $this->faker->randomNumber(),
            'actual_downloaded' => $this->faker->randomNumber(),
            'client_downloaded' => $this->faker->randomNumber(),
            'seeder'            => $this->faker->boolean,
            'active'            => $this->faker->boolean,
            'seedtime'          => $this->faker->randomNumber(),
            'immune'            => $this->faker->boolean,
            'hitrun'            => $this->faker->boolean,
            'prewarn'           => $this->faker->boolean,
            'completed_at'      => $this->faker->dateTime(),
            'deleted_at'        => $this->faker->dateTime(),
        ];
    }
}
