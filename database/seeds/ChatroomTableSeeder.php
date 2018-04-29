<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

use App\Chatroom;
use Illuminate\Database\Seeder;

class ChatroomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chatroom = Chatroom::create([
            'id' => 1,
            'name' => 'General'
        ]);

        $chatroom = Chatroom::create([
            'id' => 2,
            'name' => 'Trivia'
        ]);
        $chatroom = Chatroom::create([
            'id' => 3,
            'name' => 'System'
        ]);
    }
}