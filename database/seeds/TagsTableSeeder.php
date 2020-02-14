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

class TagsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tags')->delete();

        \DB::table('tags')->insert([
            0 => [
                'id'   => 1,
                'name' => 'Action',
                'slug' => 'action',
            ],
            1 => [
                'id'   => 2,
                'name' => 'Adventure',
                'slug' => 'adventure',
            ],
            2 => [
                'id'   => 3,
                'name' => 'Animation',
                'slug' => 'animation',
            ],
            3 => [
                'id'   => 4,
                'name' => 'Biography',
                'slug' => 'biography',
            ],
            4 => [
                'id'   => 5,
                'name' => 'Comedy',
                'slug' => 'comedy',
            ],
            5 => [
                'id'   => 6,
                'name' => 'Crime',
                'slug' => 'crime',
            ],
            6 => [
                'id'   => 7,
                'name' => 'Documentary',
                'slug' => 'documentary',
            ],
            7 => [
                'id'   => 8,
                'name' => 'Drama',
                'slug' => 'drama',
            ],
            8 => [
                'id'   => 9,
                'name' => 'Family',
                'slug' => 'family',
            ],
            9 => [
                'id'   => 10,
                'name' => 'Fantasy',
                'slug' => 'fantasy',
            ],
            10 => [
                'id'   => 11,
                'name' => 'History',
                'slug' => 'history',
            ],
            11 => [
                'id'   => 12,
                'name' => 'Horror',
                'slug' => 'horror',
            ],
            12 => [
                'id'   => 13,
                'name' => 'Music',
                'slug' => 'music',
            ],
            13 => [
                'id'   => 14,
                'name' => 'Musical',
                'slug' => 'musical',
            ],
            14 => [
                'id'   => 15,
                'name' => 'Mystery',
                'slug' => 'mystery',
            ],
            15 => [
                'id'   => 16,
                'name' => 'Romance',
                'slug' => 'romance',
            ],
            16 => [
                'id'   => 17,
                'name' => 'Science Fiction',
                'slug' => 'science-fiction',
            ],
            17 => [
                'id'   => 18,
                'name' => 'Sport',
                'slug' => 'sport',
            ],
            18 => [
                'id'   => 19,
                'name' => 'Thriller',
                'slug' => 'thriller',
            ],
            19 => [
                'id'   => 20,
                'name' => 'War',
                'slug' => 'war',
            ],
            20 => [
                'id'   => 21,
                'name' => 'Western',
                'slug' => 'western',
            ],
            21 => [
                'id'   => 22,
                'name' => 'Game-Show',
                'slug' => 'game-show',
            ],
            22 => [
                'id'   => 23,
                'name' => 'News',
                'slug' => 'news',
            ],
            23 => [
                'id'   => 24,
                'name' => 'Reality-TV',
                'slug' => 'reality-tv',
            ],
            24 => [
                'id'   => 25,
                'name' => 'Sitcom',
                'slug' => 'sitcom',
            ],
            25 => [
                'id'   => 26,
                'name' => 'Talk-Show',
                'slug' => 'talk-show',
            ],
        ]);
    }
}
