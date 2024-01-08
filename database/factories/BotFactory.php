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

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bot;

/** @extends Factory<Bot> */
class BotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Bot::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'position'     => $this->faker->randomNumber(),
            'name'         => $this->faker->name(),
            'command'      => $this->faker->word(),
            'color'        => $this->faker->word(),
            'icon'         => $this->faker->word(),
            'emoji'        => $this->faker->emoji(),
            'info'         => $this->faker->word(),
            'about'        => $this->faker->word(),
            'help'         => $this->faker->text(),
            'active'       => $this->faker->boolean(),
            'is_protected' => $this->faker->boolean(),
            'is_triviabot' => $this->faker->boolean(),
            'is_nerdbot'   => $this->faker->boolean(),
            'is_systembot' => $this->faker->boolean(),
            'is_casinobot' => $this->faker->boolean(),
            'is_betbot'    => $this->faker->boolean(),
            'uploaded'     => $this->faker->randomNumber(),
            'downloaded'   => $this->faker->randomNumber(),
            'fl_tokens'    => $this->faker->randomNumber(),
            'seedbonus'    => $this->faker->randomFloat(),
            'invites'      => $this->faker->randomNumber(),
        ];
    }
}
