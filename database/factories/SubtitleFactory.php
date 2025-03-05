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

use App\Models\MediaLanguage;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subtitle;

/** @extends Factory<Subtitle> */
class SubtitleFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title'        => $this->faker->sentence(),
            'file_name'    => $this->faker->word(),
            'file_size'    => $this->faker->randomNumber(),
            'language_id'  => MediaLanguage::factory(),
            'extension'    => $this->faker->word(),
            'note'         => $this->faker->text(),
            'downloads'    => $this->faker->randomNumber(),
            'verified'     => $this->faker->boolean(),
            'user_id'      => User::factory(),
            'torrent_id'   => Torrent::factory(),
            'anon'         => $this->faker->boolean(),
            'status'       => 1,
            'moderated_at' => $this->faker->dateTime(),
            'moderated_by' => User::factory(),
        ];
    }
}
