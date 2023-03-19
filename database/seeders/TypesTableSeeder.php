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
                'position' => 0,
            ],
            [
                'id'       => 2,
                'name'     => 'Remux',
                'position' => 1,
            ],
            [
                'id'       => 3,
                'name'     => 'Encode',
                'position' => 2,
            ],
            [
                'id'       => 4,
                'name'     => 'WEB-DL',
                'position' => 4,
            ],
            [
                'id'       => 5,
                'name'     => 'WEBRip',
                'position' => 5,
            ],
            [
                'id'       => 6,
                'name'     => 'HDTV',
                'position' => 6,
            ],
            [
                'id'       => 7,
                'name'     => 'FLAC',
                'position' => 7,
            ],
            [
                'id'       => 8,
                'name'     => 'ALAC',
                'position' => 8,
            ],
            [
                'id'       => 9,
                'name'     => 'AC3',
                'position' => 9,
            ],
            [
                'id'       => 10,
                'name'     => 'AAC',
                'position' => 10,
            ],
            [
                'id'       => 11,
                'name'     => 'MP3',
                'position' => 11,
            ],

            [
                'id'       => 12,
                'name'     => 'Mac',
                'position' => 12,
            ],
            [
                'id'       => 13,
                'name'     => 'Windows',
                'position' => 13,
            ],
        ];
    }
}
