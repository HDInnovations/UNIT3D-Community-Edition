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
use App\Models\Invite;

/** @extends Factory<Invite> */
class InviteFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'email'       => $this->faker->email(),
            'code'        => $this->faker->word(),
            'expires_on'  => $this->faker->dateTime(),
            'accepted_by' => User::factory(),
            'accepted_at' => $this->faker->dateTime(),
            'custom'      => $this->faker->text(),
        ];
    }
}
