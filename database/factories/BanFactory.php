<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Ban;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ban::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owned_by' => function () {
                return User::factory()->create()->id;
            },
            'created_by' => function () {
                return User::factory()->create()->id;
            },
            'ban_reason'   => $this->faker->text,
            'unban_reason' => $this->faker->text,
            'removed_at'   => $this->faker->dateTime(),
        ];
    }
}
