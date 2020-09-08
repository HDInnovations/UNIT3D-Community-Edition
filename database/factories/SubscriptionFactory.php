<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Subscription::class;

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
        'forum_id' => function () {
            return Forum::factory()->create()->id;
        },
        'topic_id' => function () {
            return Topic::factory()->create()->id;
        },
    ];
    }
}
