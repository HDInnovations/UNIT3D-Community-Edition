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

            6 => [
                'id'       => 7,
                'name'     => 'FLAC',
                'slug'     => 'flac',
                'position' => 7,
            ],
            7 => [
                'id'       => 8,
                'name'     => 'ALAC',
                'slug'     => 'alac',
                'position' => 8,
            ],
            8 => [
                'id'       => 9,
                'name'     => 'AC3',
                'slug'     => 'ac3',
                'position' => 9,
            ],
            9 => [
                'id'       => 10,
                'name'     => 'AAC',
                'slug'     => 'aac',
                'position' => 10,
            ],
            10 => [
                'id'       => 11,
                'name'     => 'MP3',
                'slug'     => 'mp3',
                'position' => 11,
            ],

            11 => [
                'id'       => 12,
                'name'     => 'Mac',
                'slug'     => 'mac',
                'position' => 12,
            ],
            12 => [
                'id'       => 13,
                'name'     => 'Windows',
                'slug'     => 'windows',
                'position' => 13,
            ],
        ]);
    }
}
