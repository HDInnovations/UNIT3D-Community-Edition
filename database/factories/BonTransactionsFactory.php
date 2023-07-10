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
 *//**
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

use App\Models\BonExchange;
use App\Models\Post;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BonTransactions;

class BonTransactionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = BonTransactions::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'itemID'        => BonExchange::factory(),
            'name'          => $this->faker->name(),
            'cost'          => $this->faker->randomFloat(),
            'sender'        => User::factory(),
            'receiver'      => User::factory(),
            'torrent_id'    => Torrent::factory(),
            'post_id'       => Post::factory(),
            'comment'       => $this->faker->text(),
            'date_actioned' => $this->faker->dateTime(),
        ];
    }
}
