<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BonExchange;

class BonExchangeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BonExchange::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value'              => $this->faker->randomNumber(),
            'cost'               => $this->faker->randomNumber(),
            'upload'             => $this->faker->boolean(),
            'download'           => $this->faker->boolean(),
            'personal_freeleech' => $this->faker->boolean(),
            'invite'             => $this->faker->boolean(),
        ];
    }
}
