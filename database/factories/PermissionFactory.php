<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'forum_id' => function () {
            return Forum::factory()->create()->id;
        },
        'group_id' => function () {
            return Group::factory()->create()->id;
        },
        'show_forum'  => $this->faker->boolean,
        'read_topic'  => $this->faker->boolean,
        'reply_topic' => $this->faker->boolean,
        'start_topic' => $this->faker->boolean,
    ];
    }
}
