<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\TorrentRequest;
use App\Models\TorrentRequestBounty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TorrentRequestBountyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TorrentRequestBounty::class;

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
            'seedbonus'   => $this->faker->randomFloat(),
            'requests_id' => $this->faker->randomNumber(),
            'anon'        => $this->faker->boolean,
            'request_id'  => function () {
                return TorrentRequest::factory()->create()->id;
            },
        ];
    }
}
