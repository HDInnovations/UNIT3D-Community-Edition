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

use App\Models\Tv;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Season;

/** @extends Factory<Season> */
class SeasonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Season::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tv_id'         => Tv::factory(),
            'season_number' => $this->faker->randomNumber(),
            'name'          => $this->faker->name(),
            'overview'      => $this->faker->text(),
            'poster'        => $this->faker->word(),
            'air_date'      => $this->faker->word(),
            'created_at'    => $this->faker->dateTime(),
            'updated_at'    => $this->faker->dateTime(),
        ];
    }
}
