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
                'slug'        => 'movies',
                'position'    => 0,
                'icon'        => config('other.font-awesome').' fa-film',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 1,
                'tv_meta'     => 0,
                'game_meta'   => 0,
                'music_meta'  => 0,
                'no_meta'     => 0,
            ],
            [
                'id'          => 2,
                'name'        => 'TV',
                'slug'        => 'tv',
                'position'    => 1,
                'icon'        => config('other.font-awesome').' fa-tv-retro',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 0,
                'tv_meta'     => 1,
                'game_meta'   => 0,
                'music_meta'  => 0,
                'no_meta'     => 0,
            ],
            [
                'id'          => 3,
                'name'        => 'Music',
                'slug'        => 'music',
                'position'    => 2,
                'icon'        => config('other.font-awesome').' fa-music',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 0,
                'tv_meta'     => 0,
                'game_meta'   => 0,
                'music_meta'  => 1,
                'no_meta'     => 0,
            ],
            [
                'id'          => 4,
                'name'        => 'Game',
                'slug'        => 'game',
                'position'    => 3,
                'icon'        => config('other.font-awesome').' fa-gamepad',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 0,
                'tv_meta'     => 0,
                'game_meta'   => 1,
                'music_meta'  => 0,
                'no_meta'     => 0,
            ],
            [
                'id'          => 5,
                'name'        => 'Application',
                'slug'        => 'application',
                'position'    => 4,
                'icon'        => config('other.font-awesome').' fa-compact-disc',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 0,
                'tv_meta'     => 0,
                'game_meta'   => 0,
                'music_meta'  => 0,
                'no_meta'     => 1,
            ],
        ];
    }
}
