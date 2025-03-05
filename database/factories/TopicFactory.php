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

use App\Models\Forum;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Topic;

/** @extends Factory<Topic> */
class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'                 => $this->faker->name(),
            'state'                => $this->faker->word(),
            'priority'             => $this->faker->randomNumber(),
            'approved'             => $this->faker->boolean(),
            'denied'               => $this->faker->boolean(),
            'solved'               => $this->faker->boolean(),
            'invalid'              => $this->faker->boolean(),
            'bug'                  => $this->faker->boolean(),
            'suggestion'           => $this->faker->boolean(),
            'implemented'          => $this->faker->boolean(),
            'num_post'             => $this->faker->randomNumber(),
            'first_post_user_id'   => null,
            'last_post_id'         => null,
            'last_post_user_id'    => null,
            'last_post_created_at' => $this->faker->dateTime(),
            'views'                => $this->faker->randomNumber(),
            'forum_id'             => Forum::factory(),
        ];
    }
}
