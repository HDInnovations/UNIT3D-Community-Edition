<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Voter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'poll_id' => function () {
            return Poll::factory()->create()->id;
        },
        'user_id' => function () {
            return User::factory()->create()->id;
        },
        'ip_address' => $this->faker->word,
    ];
    }
}
