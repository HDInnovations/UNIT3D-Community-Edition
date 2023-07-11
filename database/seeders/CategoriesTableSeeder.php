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

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    private $categories;

    public function __construct()
    {
        $this->categories = $this->getCategories();
    }

    /**
     * Auto generated seed file.
     */
    public function run(): void
    {
        foreach ($this->categories as $category) {
            Category::updateOrCreate($category);
        }
    }

    private function getCategories(): array
    {
        return [
            [
                'id'          => 1,
                'name'        => 'Movies',
                'position'    => 0,
                'icon'        => config('other.font-awesome').' fa-film',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => true,
                'tv_meta'     => false,
                'game_meta'   => false,
                'music_meta'  => false,
                'no_meta'     => false,
            ],
            [
                'id'          => 2,
                'name'        => 'TV',
                'position'    => 1,
                'icon'        => config('other.font-awesome').' fa-tv-retro',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => false,
                'tv_meta'     => true,
                'game_meta'   => false,
                'music_meta'  => false,
                'no_meta'     => false,
            ],
            [
                'id'          => 3,
                'name'        => 'Music',
                'position'    => 2,
                'icon'        => config('other.font-awesome').' fa-music',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => false,
                'tv_meta'     => false,
                'game_meta'   => false,
                'music_meta'  => true,
                'no_meta'     => false,
            ],
            [
                'id'          => 4,
                'name'        => 'Game',
                'position'    => 3,
                'icon'        => config('other.font-awesome').' fa-gamepad',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => false,
                'tv_meta'     => false,
                'game_meta'   => true,
                'music_meta'  => false,
                'no_meta'     => false,
            ],
            [
                'id'          => 5,
                'name'        => 'Application',
                'position'    => 4,
                'icon'        => config('other.font-awesome').' fa-compact-disc',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => false,
                'tv_meta'     => false,
                'game_meta'   => false,
                'music_meta'  => false,
                'no_meta'     => true,
            ],
        ];
    }
}
