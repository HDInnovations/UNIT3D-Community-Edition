<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BonExchangeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\BonExchange::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description'        => $this->faker->text,
            'value'              => $this->faker->randomNumber(),
            'cost'               => $this->faker->randomNumber(),
            'upload'             => $this->faker->boolean,
            'download'           => $this->faker->boolean,
            'personal_freeleech' => $this->faker->boolean,
            'invite'             => $this->faker->boolean,
        ];
    }
}
