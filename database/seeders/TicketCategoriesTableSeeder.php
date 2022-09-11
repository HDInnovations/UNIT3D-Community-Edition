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

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;

class TicketCategoriesTableSeeder extends Seeder
{
    private array $categories;

    public function __construct()
    {
        $this->categories = $this->getTicketCategories();
    }

    /**
     * Auto generated seed file.
     *
     * @return voids
     */
    final public function run(): void
    {
        foreach ($this->categories as $category) {
            TicketCategory::updateOrCreate($category);
        }
    }

    /**
     * @return array[]
     */
    private function getTicketCategories(): array
    {
        return [
            [
                'name'     => 'Accounts',
                'position' => 0,
            ],
            [
                'name'     => 'Appeals',
                'position' => 1,
            ],
            [
                'name'     => 'Forums',
                'position' => 2,
            ],
            [
                'name'     => 'Requests',
                'position' => 3,
            ],
            [
                'name'     => 'Subtitles',
                'position' => 4,
            ],
            [
                'name'     => 'Torrents',
                'position' => 5,
            ],
            [
                'name'     => 'MediaHub',
                'position' => 6,
            ],
            [
                'name'     => 'Technical',
                'position' => 7,
            ],
            [
                'name'     => 'Playlists',
                'position' => 8,
            ],
            [
                'name'     => 'Bugs',
                'position' => 9,
            ],
            [
                'name'     => 'Other',
                'position' => 10,
            ],
        ];
    }
}
