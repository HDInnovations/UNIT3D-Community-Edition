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

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    private $types;

    public function __construct()
    {
        $this->types = $this->getTypes();
    }

    /**
     * Auto generated seed file.
     */
    public function run(): void
    {
        foreach ($this->types as $type) {
            Type::updateOrCreate($type);
        }
    }

    private function getTypes(): array
    {
        return [
            [
                'id'       => 1,
                'name'     => 'Full Disc',
                'slug'     => 'full-disc',
                'position' => 0,
            ],
            [
                'id'       => 2,
                'name'     => 'Remux',
                'slug'     => 'remux',
                'position' => 1,
            ],
            [
                'id'       => 3,
                'name'     => 'Encode',
                'slug'     => 'encode',
                'position' => 2,
            ],
            [
                'id'       => 4,
                'name'     => 'WEB-DL',
                'slug'     => 'web-dl',
                'position' => 4,
            ],
            [
                'id'       => 5,
                'name'     => 'WEBRip',
                'slug'     => 'web-rip',
                'position' => 5,
            ],
            [
                'id'       => 6,
                'name'     => 'HDTV',
                'slug'     => 'hdtv',
                'position' => 6,
            ],
            [
                'id'       => 7,
                'name'     => 'FLAC',
                'slug'     => 'flac',
                'position' => 7,
            ],
            [
                'id'       => 8,
                'name'     => 'ALAC',
                'slug'     => 'alac',
                'position' => 8,
            ],
            [
                'id'       => 9,
                'name'     => 'AC3',
                'slug'     => 'ac3',
                'position' => 9,
            ],
            [
                'id'       => 10,
                'name'     => 'AAC',
                'slug'     => 'aac',
                'position' => 10,
            ],
            [
                'id'       => 11,
                'name'     => 'MP3',
                'slug'     => 'mp3',
                'position' => 11,
            ],

            [
                'id'       => 12,
                'name'     => 'Mac',
                'slug'     => 'mac',
                'position' => 12,
            ],
            [
                'id'       => 13,
                'name'     => 'Windows',
                'slug'     => 'windows',
                'position' => 13,
            ],
        ];
    }
}
