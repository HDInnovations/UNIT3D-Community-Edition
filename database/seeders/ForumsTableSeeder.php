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

use App\Models\Forum;
use Illuminate\Database\Seeder;

class ForumsTableSeeder extends Seeder
{
    private $forums;

    public function __construct()
    {
        $this->forums = $this->getForums();
    }

    /**
     * Auto generated seed file.
     */
    public function run(): void
    {
        foreach ($this->forums as $forum) {
            Forum::updateOrCreate($forum);
        }
    }

    private function getForums(): array
    {
        return [
            [
                'id'                      => 1,
                'position'                => 1,
                'num_topic'               => null,
                'num_post'                => null,
                'last_topic_id'           => null,
                'last_topic_name'         => null,
                'last_post_user_id'       => null,
                'last_post_user_username' => null,
                'name'                    => 'UNIT3D Forums',
                'slug'                    => 'unit3d-forums',
                'description'             => 'UNIT3D Forums',
                'parent_id'               => 0,
                'created_at'              => '2017-01-03 18:29:21',
                'updated_at'              => '2017-01-03 18:29:21',
            ],
            [
                'id'                      => 2,
                'position'                => 2,
                'num_topic'               => null,
                'num_post'                => null,
                'last_topic_id'           => null,
                'last_topic_name'         => null,
                'last_post_user_id'       => null,
                'last_post_user_username' => null,
                'name'                    => 'Welcome',
                'slug'                    => 'welcome',
                'description'             => 'Introduce Yourself Here!',
                'parent_id'               => 1,
                'created_at'              => '2017-04-01 20:16:06',
                'updated_at'              => '2017-12-27 18:19:07',
            ],
        ];
    }
}
