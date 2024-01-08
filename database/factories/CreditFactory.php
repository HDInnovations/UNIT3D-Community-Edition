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

use App\Models\Movie;
use App\Models\Occupation;
use App\Models\Person;
use App\Models\Tv;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Credit;

/** @extends Factory<Credit> */
class CreditFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Credit::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'person_id'     => Person::factory(),
            'movie_id'      => Movie::factory(),
            'tv_id'         => Tv::factory(),
            'occupation_id' => Occupation::factory(),
            'order'         => $this->faker->randomNumber(),
            'character'     => $this->faker->unique()->word(),
        ];
    }
}
