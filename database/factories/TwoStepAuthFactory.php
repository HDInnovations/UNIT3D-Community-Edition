<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\TwoStepAuth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TwoStepAuthFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TwoStepAuth::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'userId'      => $this->faker->randomNumber(),
            'authCode'    => sprintf('%s%s%s%s', $this->faker->numberBetween(0, 9), $this->faker->numberBetween(0, 9), $this->faker->numberBetween(0, 9), $this->faker->numberBetween(0, 9)),
            'authCount'   => 0,
            'authStatus'  => false,
            'authDate'    => null,
            'requestDate' => Carbon::now(),
        ];
    }
}
