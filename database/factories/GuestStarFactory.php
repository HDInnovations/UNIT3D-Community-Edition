<?php

namespace Database\Factories;

use App\Models\GuestStar;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuestStarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GuestStar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
