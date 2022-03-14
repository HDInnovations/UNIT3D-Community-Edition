<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AutoPrivileges;

class AutoPrivilegesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AutoPrivileges::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'accountAge' => $this->faker->boolean,
            'bonBalance' => $this->faker->boolean,
            'buffer' => $this->faker->boolean,
            'commentCount' => $this->faker->boolean,
            'download' => $this->faker->boolean,
            'downloadCount' => $this->faker->boolean,
            'downloadPurchase' => $this->faker->boolean,
            'enabled' => $this->faker->boolean,
            'inviteBalance' => $this->faker->boolean,
            'inviteCount' => $this->faker->boolean,
            'postCount' => $this->faker->boolean,
            'privilege_id' => \App\REPLACE_THIS::factory(),
            'ratio' => $this->faker->boolean,
            'requestCount' => $this->faker->boolean,
            'type' => $this->faker->word,
            'upload' => $this->faker->boolean,
            'uploadCount' => $this->faker->boolean,
            'uploadPurchase' => $this->faker->boolean,
            'warningsBalance' => $this->faker->boolean,
        ];
    }
}
