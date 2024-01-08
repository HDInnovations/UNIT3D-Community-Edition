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

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Forum;

/** @extends Factory<Forum> */
class ForumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Forum::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'position'                => $this->faker->randomNumber(),
            'num_topic'               => $this->faker->randomNumber(),
            'num_post'                => $this->faker->randomNumber(),
            'last_topic_id'           => $this->faker->randomDigitNotNull(),
            'last_topic_name'         => $this->faker->word(),
            'last_post_user_id'       => User::factory(),
            'last_post_user_username' => $this->faker->word(),
            'name'                    => $this->faker->name(),
            'slug'                    => $this->faker->slug(),
            'description'             => $this->faker->text(),
            'parent_id'               => $this->faker->randomDigitNotNull(),
        ];
    }
}
