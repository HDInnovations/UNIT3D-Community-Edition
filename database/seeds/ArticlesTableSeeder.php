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

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('articles')->delete();

        \DB::table('articles')->insert([
            0 => [
                'id'         => 1,
                'title'      => 'Welcome To '.config('other.title').' .',
                'slug'       => 'welcome',
                'content'    => 'Welcome to '.config('other.title').'. Powered By '.config('other.codebase').'.',
                'user_id'    => 3,
                'created_at' => '2017-02-28 17:22:37',
                'updated_at' => '2017-04-21 12:21:06',
            ],
        ]);
    }
}
