<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BonExchange;

/** @extends Factory<BonExchange> */
class BonExchangeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'description'        => $this->faker->word(),
            'value'              => $this->faker->randomNumber(),
            'cost'               => $this->faker->randomNumber(),
            'upload'             => $this->faker->boolean(),
            'download'           => $this->faker->boolean(),
            'personal_freeleech' => $this->faker->boolean(),
            'invite'             => $this->faker->boolean(),
        ];
    }
}
