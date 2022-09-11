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

use App\Models\TicketPriority;
use Illuminate\Database\Seeder;

class TicketPrioritiesTableSeeder extends Seeder
{
    private array $priorities;

    public function __construct()
    {
        $this->priorities = $this->getTicketPriorities();
    }

    /**
     * Auto generated seed file.
     *
     * @return voids
     */
    final public function run(): void
    {
        foreach ($this->priorities as $priority) {
            TicketPriority::updateOrCreate($priority);
        }
    }

    /**
     * @return array[]
     */
    private function getTicketPriorities(): array
    {
        return [
            [
                'name'     => 'Low',
                'position' => 0,
            ],
            [
                'name'     => 'Medium',
                'position' => 1,
            ],
            [
                'name'     => 'High',
                'position' => 2,
            ],
        ];
    }
}
