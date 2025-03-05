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
use App\Models\History;

/** @extends Factory<History> */
class HistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'           => User::factory(),
            'torrent_id'        => Torrent::factory(),
            'agent'             => $this->faker->word(),
            'uploaded'          => $this->faker->randomNumber(),
            'actual_uploaded'   => $this->faker->randomNumber(),
            'client_uploaded'   => $this->faker->randomNumber(),
            'downloaded'        => $this->faker->randomNumber(),
            'refunded_download' => $this->faker->randomNumber(),
            'actual_downloaded' => $this->faker->randomNumber(),
            'client_downloaded' => $this->faker->randomNumber(),
            'seeder'            => $this->faker->boolean(),
            'active'            => $this->faker->boolean(),
            'seedtime'          => $this->faker->randomNumber(),
            'immune'            => $this->faker->boolean(),
            'hitrun'            => $this->faker->boolean(),
            'prewarned_at'      => $this->faker->dateTime(),
            'completed_at'      => $this->faker->dateTime(),
        ];
    }
}
