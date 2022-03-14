<?php

namespace Database\Factories;

use App\Models\AutoRoles;
use Illuminate\Database\Eloquent\Factories\Factory;

class AutoRolesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'accountAge'       => $this->faker->boolean,
            'bonBalance'       => $this->faker->boolean,
            'buffer'           => $this->faker->boolean,
            'commentCount'     => $this->faker->boolean,
            'download'         => $this->faker->boolean,
            'downloadCount'    => $this->faker->boolean,
            'downloadPurchase' => $this->faker->boolean,
            'enabled'          => $this->faker->boolean,
            'inviteBalance'    => $this->faker->boolean,
            'inviteCount'      => $this->faker->boolean,
            'leechingCount'    => $this->faker->boolean,
            'postCount'        => $this->faker->boolean,
            'ratio'            => $this->faker->boolean,
            'requestCount'     => $this->faker->boolean,
            'role_id'          => \App\Models\Role::factory(),
            'seedingCount'     => $this->faker->boolean,
            'type'             => $this->faker->word,
            'upload'           => $this->faker->boolean,
            'uploadCount'      => $this->faker->boolean,
            'uploadPurchase'   => $this->faker->boolean,
            'warningsBalance'  => $this->faker->boolean,
        ];
    }
}
