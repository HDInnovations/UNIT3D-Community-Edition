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

use App\Models\Torrent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Warning;

/** @extends Factory<Warning> */
class WarningFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'warned_by'  => User::factory(),
            'torrent'    => Torrent::factory(),
            'reason'     => $this->faker->text(),
            'expires_on' => $this->faker->dateTime(),
            'active'     => $this->faker->boolean(),
            'deleted_by' => User::factory(),
        ];
    }
}
