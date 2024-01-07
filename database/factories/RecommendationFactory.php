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
use App\Models\Tv;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Recommendation;

/** @extends Factory<Recommendation> */
class RecommendationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Recommendation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title'                   => $this->faker->sentence(),
            'poster'                  => $this->faker->word(),
            'vote_average'            => $this->faker->word(),
            'release_date'            => $this->faker->date(),
            'first_air_date'          => $this->faker->date(),
            'movie_id'                => Movie::factory(),
            'recommendation_movie_id' => $this->faker->unique()->randomDigitNotNull(),
            'tv_id'                   => Tv::factory(),
            'recommendation_tv_id'    => $this->faker->unique()->randomDigitNotNull(),
        ];
    }
}
