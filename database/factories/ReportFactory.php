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

use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Report;

/** @extends Factory<Report> */
class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Report::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type'          => $this->faker->word(),
            'reporter_id'   => User::factory(),
            'staff_id'      => User::factory(),
            'title'         => $this->faker->sentence(),
            'message'       => $this->faker->text(),
            'solved'        => $this->faker->randomNumber(),
            'verdict'       => $this->faker->text(),
            'reported_user' => User::factory(),
            'torrent_id'    => Torrent::factory(),
            'request_id'    => TorrentRequest::factory(),
        ];
    }
}
