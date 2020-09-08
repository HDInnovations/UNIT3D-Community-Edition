<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrivateMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\PrivateMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'sender_id' => function () {
            return User::factory()->create()->id;
        },
        'receiver_id' => function () {
            return User::factory()->create()->id;
        },
        'subject'    => $this->faker->word,
        'message'    => $this->faker->text,
        'read'       => $this->faker->boolean,
        'related_to' => $this->faker->randomNumber(),
    ];
    }
}
