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

use App\Models\Poll;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Poll> */
class PollFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Poll::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'         => User::factory(),
            'title'           => $this->faker->sentence(),
            'multiple_choice' => $this->faker->boolean(),
        ];
    }
}
