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

use App\Models\Chatroom;
use Illuminate\Database\Seeder;

class ChatroomTableSeeder extends Seeder
{
    private $rooms;

    public function __construct()
    {
        $this->rooms = $this->getRooms();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->rooms as $room) {
            Chatroom::updateOrCreate($room);
        }
    }

    private function getRooms(): array
    {
        return [
            [
                'name' => 'General',
            ],
            [
                'name' => 'Trivia',
            ],
        ];
    }
}
