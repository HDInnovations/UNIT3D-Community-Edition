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
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ForumPermission;

/** @extends Factory<ForumPermission> */
class ForumPermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'forum_id'    => Forum::factory(),
            'group_id'    => Group::factory(),
            'read_topic'  => $this->faker->boolean(),
            'reply_topic' => $this->faker->boolean(),
            'start_topic' => $this->faker->boolean(),
        ];
    }
}
