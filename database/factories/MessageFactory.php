<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Bot;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Message::class;

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
        'chatroom_id' => function () {
            return Chatroom::factory()->create()->id;
        },
        'receiver_id' => function () {
            return User::factory()->create()->id;
        },
        'bot_id' => function () {
            return Bot::factory()->create()->id;
        },
        'message' => $this->faker->text,
    ];
    }
}
