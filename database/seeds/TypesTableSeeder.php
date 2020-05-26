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

class TypesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('types')->delete();

        \DB::table('types')->insert([
            0 => [
                'id'       => 1,
                'name'     => 'Full Disc',
                'slug'     => 'full-disc',
                'position' => 0,
            ],
            1 => [
                'id'       => 2,
                'name'     => 'Remux',
                'slug'     => 'remux',
                'position' => 1,
            ],
            2 => [
                'id'       => 3,
                'name'     => 'Encode',
                'slug'     => 'encode',
                'position' => 2,
            ],
            3 => [
                'id'       => 4,
                'name'     => 'WEB-DL',
                'slug'     => 'web-dl',
                'position' => 4,
            ],
            4 => [
                'id'       => 5,
                'name'     => 'WEBRip',
                'slug'     => 'web-rip',
                'position' => 5,
            ],
            5 => [
                'id'       => 6,
                'name'     => 'HDTV',
                'slug'     => 'hdtv',
                'position' => 6,
            ],
        ]);
    }
}
