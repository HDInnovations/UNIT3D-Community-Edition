<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserSetting;

/**
 * @extends Factory<UserSetting>
 */
class UserSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = UserSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'censor'              => $this->faker->boolean(),
            'chat_hidden'         => $this->faker->boolean(),
            'hidden'              => $this->faker->boolean(),
            'style'               => $this->faker->boolean(),
            'torrent_layout'      => $this->faker->boolean(),
            'torrent_filters'     => $this->faker->boolean(),
            'custom_css'          => $this->faker->word(),
            'standalone_css'      => $this->faker->word(),
            'show_poster'         => $this->faker->boolean(),
            'private_profile'     => $this->faker->boolean(),
            'block_notifications' => $this->faker->boolean(),
        ];
    }
}
