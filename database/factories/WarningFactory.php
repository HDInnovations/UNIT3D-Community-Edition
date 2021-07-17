<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Torrent;
use App\Models\User;
use App\Models\Warning;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarningFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warning::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'    => fn () => User::factory()->create()->id,
            'warned_by'  => fn () => User::factory()->create()->id,
            'torrent'    => fn () => Torrent::factory()->create()->id,
            'reason'     => $this->faker->text,
            'expires_on' => $this->faker->dateTime(),
            'active'     => $this->faker->boolean,
            'deleted_by' => fn () => User::factory()->create()->id,
        ];
    }
}
