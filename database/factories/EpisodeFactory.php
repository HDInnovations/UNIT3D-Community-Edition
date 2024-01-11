<?php
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

use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Episode;

/** @extends Factory<Episode> */
class EpisodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Episode::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'            => $this->faker->name(),
            'overview'        => $this->faker->text(),
            'production_code' => $this->faker->word(),
            'season_number'   => $this->faker->randomNumber(),
            'season_id'       => Season::factory(),
            'still'           => $this->faker->word(),
            'tv_id'           => $this->faker->randomDigitNotNull(),
            'type'            => $this->faker->word(),
            'vote_average'    => $this->faker->word(),
            'vote_count'      => $this->faker->randomNumber(),
            'air_date'        => $this->faker->word(),
            'episode_number'  => $this->faker->randomNumber(),
        ];
    }
}
