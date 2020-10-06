<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type'            => $this->faker->word,
            'notifiable_id'   => $this->faker->randomNumber(),
            'notifiable_type' => $this->faker->word,
            'data'            => $this->faker->text,
            'read_at'         => $this->faker->dateTime(),
        ];
    }
}
