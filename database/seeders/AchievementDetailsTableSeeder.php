<?php

declare(strict_types=1);
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

use Assada\Achievements\Model\AchievementDetails as AchievementDetail;
use Illuminate\Database\Seeder;

class AchievementDetailsTableSeeder extends Seeder
{
    private $achievementDetails;

    public function __construct()
    {
        $this->achievementDetails = $this->getAchievementDetails();
    }

    /**
     * Auto generated seed file.
     */
    public function run(): void
    {
        foreach ($this->achievementDetails as $ad) {
            AchievementDetail::updateOrCreate($ad);
        }
    }

    private function getAchievementDetails(): array
    {
        return [
            [
                'id'          => 2,
                'name'        => 'FirstComment',
                'description' => 'Congratulations! You have made your first comment!',
                'points'      => 1,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMadeComment::class,
                'created_at'  => '2017-02-28 17:22:37',
                'updated_at'  => '2017-04-21 12:52:01',
            ],
            [
                'id'          => 3,
                'name'        => '10Comments',
                'description' => 'Wow! You have already made 10 comments!',
                'points'      => 10,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMadeTenComments::class,
                'created_at'  => '2017-02-28 17:22:37',
                'updated_at'  => '2017-04-21 12:21:06',
            ],
            [
                'id'          => 4,
                'name'        => 'FirstUpload',
                'description' => 'Congratulations! You have made your first torrent upload!',
                'points'      => 1,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMadeUpload::class,
                'created_at'  => '2017-03-01 13:31:50',
                'updated_at'  => '2017-03-22 14:59:32',
            ],
            [
                'id'          => 5,
                'name'        => '25Uploads',
                'description' => 'You have made 25 torrent uploads!',
                'points'      => 25,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade25Uploads::class,
                'created_at'  => '2017-03-02 23:19:34',
                'updated_at'  => '2017-04-21 12:21:06',
            ],
            [
                'id'          => 6,
                'name'        => '50Comments',
                'description' => 'Wow! You have already made 50 comments!',
                'points'      => 50,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade50Comments::class,
                'created_at'  => '2017-04-21 13:04:26',
                'updated_at'  => '2017-04-21 13:04:26',
            ],
            [
                'id'          => 7,
                'name'        => '100Comments',
                'description' => 'Wow! You have already made 100 comments!',
                'points'      => 100,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade100Comments::class,
                'created_at'  => '2017-04-21 13:04:26',
                'updated_at'  => '2017-04-21 13:04:26',
            ],
            [
                'id'          => 8,
                'name'        => '200Comments',
                'description' => 'Wow! You have already made 200 comments!',
                'points'      => 200,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade200Comments::class,
                'created_at'  => '2017-04-21 13:04:26',
                'updated_at'  => '2017-04-21 13:04:26',
            ],
            [
                'id'          => 9,
                'name'        => '300Comments',
                'description' => 'Wow! You have already made 300 comments!',
                'points'      => 300,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade300Comments::class,
                'created_at'  => '2017-04-21 13:04:26',
                'updated_at'  => '2017-04-21 13:04:26',
            ],
            [
                'id'          => 10,
                'name'        => '400Comments',
                'description' => 'Wow! You have already made 400 comments!',
                'points'      => 400,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade400Comments::class,
                'created_at'  => '2017-04-21 13:04:26',
                'updated_at'  => '2017-04-21 13:04:26',
            ],
            [
                'id'          => 11,
                'name'        => '500Comments',
                'description' => 'Wow! You have already made 500 comments!',
                'points'      => 500,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade500Comments::class,
                'created_at'  => '2017-04-21 13:04:26',
                'updated_at'  => '2017-04-21 13:04:26',
            ],
            [
                'id'          => 12,
                'name'        => '600Comments',
                'description' => 'Wow! You have already made 600 comments!',
                'points'      => 600,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade600Comments::class,
                'created_at'  => '2017-04-21 13:04:26',
                'updated_at'  => '2017-04-21 13:04:26',
            ],
            [
                'id'          => 13,
                'name'        => '700Comments',
                'description' => 'Wow! You have already made 700 comments!',
                'points'      => 700,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade700Comments::class,
                'created_at'  => '2017-04-21 13:04:26',
                'updated_at'  => '2017-04-21 13:04:26',
            ],
            [
                'id'          => 14,
                'name'        => '800Comments',
                'description' => 'Wow! You have already made 800 comments!',
                'points'      => 800,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade800Comments::class,
                'created_at'  => '2017-04-21 13:04:26',
                'updated_at'  => '2017-04-21 13:04:26',
            ],
            [
                'id'          => 15,
                'name'        => '900Comments',
                'description' => 'DAMN BRO! You have made 900 comments!',
                'points'      => 900,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade900Comments::class,
                'created_at'  => '2017-04-21 13:04:26',
                'updated_at'  => '2017-04-21 13:04:26',
            ],
            [
                'id'          => 16,
                'name'        => '50Uploads',
                'description' => 'You have made 50 torrent uploads!',
                'points'      => 50,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade50Uploads::class,
                'created_at'  => '2017-04-21 13:29:51',
                'updated_at'  => '2017-04-21 13:29:51',
            ],
            [
                'id'          => 17,
                'name'        => '100Uploads',
                'description' => 'You have made 100 torrent uploads!',
                'points'      => 100,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade100Uploads::class,
                'created_at'  => '2017-04-21 13:29:51',
                'updated_at'  => '2017-04-21 13:29:51',
            ],
            [
                'id'          => 18,
                'name'        => '200Uploads',
                'description' => 'You have made 200 torrent uploads!',
                'points'      => 200,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade200Uploads::class,
                'created_at'  => '2017-04-21 13:29:51',
                'updated_at'  => '2017-04-21 13:29:51',
            ],
            [
                'id'          => 19,
                'name'        => '300Uploads',
                'description' => 'You have made 300 torrent uploads!',
                'points'      => 300,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade300Uploads::class,
                'created_at'  => '2017-04-21 13:29:51',
                'updated_at'  => '2017-04-21 13:29:51',
            ],
            [
                'id'          => 20,
                'name'        => '400Uploads',
                'description' => 'You have made 400 torrent uploads!',
                'points'      => 400,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade400Uploads::class,
                'created_at'  => '2017-04-21 13:29:51',
                'updated_at'  => '2017-04-21 13:29:51',
            ],
            [
                'id'          => 21,
                'name'        => '500Uploads',
                'description' => 'You have made 500 torrent uploads!',
                'points'      => 500,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade500Uploads::class,
                'created_at'  => '2017-04-21 13:29:51',
                'updated_at'  => '2017-04-21 13:29:51',
            ],
            [
                'id'          => 22,
                'name'        => '600Uploads',
                'description' => 'You have made 600 torrent uploads!',
                'points'      => 600,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade600Uploads::class,
                'created_at'  => '2017-04-21 13:29:51',
                'updated_at'  => '2017-04-21 13:29:51',
            ],
            [
                'id'          => 23,
                'name'        => '700Uploads',
                'description' => 'You have made 700 torrent uploads!',
                'points'      => 700,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade700Uploads::class,
                'created_at'  => '2017-04-21 13:29:51',
                'updated_at'  => '2017-04-21 13:29:51',
            ],
            [
                'id'          => 24,
                'name'        => '800Uploads',
                'description' => 'You have made 800 torrent uploads!',
                'points'      => 800,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade800Uploads::class,
                'created_at'  => '2017-04-21 13:29:51',
                'updated_at'  => '2017-04-21 13:29:51',
            ],
            [
                'id'          => 25,
                'name'        => '900Uploads',
                'description' => 'DAMN BRO, you have made 900 torrent uploads!',
                'points'      => 900,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade900Uploads::class,
                'created_at'  => '2017-04-21 13:29:51',
                'updated_at'  => '2017-04-21 13:29:51',
            ],
            [
                'id'          => 26,
                'name'        => 'FirstPost',
                'description' => 'Congratulations! You have made your first post!',
                'points'      => 1,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMadeFirstPost::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:38:48',
            ],
            [
                'id'          => 27,
                'name'        => '25Posts',
                'description' => 'Wow! You have already made 25 posts!',
                'points'      => 25,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade25Posts::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:37:09',
            ],
            [
                'id'          => 28,
                'name'        => '50Posts',
                'description' => 'Wow! You have already made 50 posts!',
                'points'      => 50,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade50Posts::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:37:09',
            ],
            [
                'id'          => 29,
                'name'        => '100Posts',
                'description' => 'Wow! You have already made 100 posts!',
                'points'      => 100,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade100Posts::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:37:09',
            ],
            [
                'id'          => 30,
                'name'        => '200Posts',
                'description' => 'Wow! You have already made 200 posts!',
                'points'      => 200,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade200Posts::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:37:09',
            ],
            [
                'id'          => 31,
                'name'        => '300Posts',
                'description' => 'Wow! You have already made 300 posts!',
                'points'      => 300,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade300Posts::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:37:09',
            ],
            [
                'id'          => 32,
                'name'        => '400Posts',
                'description' => 'Wow! You have already made 400 posts!',
                'points'      => 400,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade400Posts::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:37:09',
            ],
            [
                'id'          => 33,
                'name'        => '500Posts',
                'description' => 'Wow! You have already made 500 posts!',
                'points'      => 500,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade500Posts::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:37:09',
            ],
            [
                'id'          => 34,
                'name'        => '600Posts',
                'description' => 'Wow! You have already made 600 posts!',
                'points'      => 600,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade600Posts::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:37:09',
            ],
            [
                'id'          => 35,
                'name'        => '700Posts',
                'description' => 'Wow! You have already made 700 posts!',
                'points'      => 700,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade700Posts::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:37:09',
            ],
            [
                'id'          => 36,
                'name'        => '800Posts',
                'description' => 'Wow! You have already made 800 posts!',
                'points'      => 800,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade800Posts::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:37:09',
            ],
            [
                'id'          => 37,
                'name'        => '900Posts',
                'description' => 'Wow! You have already made 900 posts!',
                'points'      => 900,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserMade900Posts::class,
                'created_at'  => '2017-04-21 18:37:09',
                'updated_at'  => '2017-04-21 18:37:09',
            ],
            [
                'id'          => 38,
                'name'        => 'Filled25Requests',
                'description' => 'Congrats! You have already filled 25 requests!',
                'points'      => 25,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserFilled25Requests::class,
                'created_at'  => '2017-08-28 23:55:56',
                'updated_at'  => '2017-08-28 23:55:56',
            ],
            [
                'id'          => 39,
                'name'        => 'Filled50Requests',
                'description' => 'Wow! You have already filled 50 requests!',
                'points'      => 50,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserFilled50Requests::class,
                'created_at'  => '2017-08-28 23:55:56',
                'updated_at'  => '2017-08-28 23:55:56',
            ],
            [
                'id'          => 40,
                'name'        => 'Filled75Requests',
                'description' => 'Woot! You have already filled 75 requests!',
                'points'      => 75,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserFilled75Requests::class,
                'created_at'  => '2017-08-28 23:55:56',
                'updated_at'  => '2017-08-28 23:55:56',
            ],
            [
                'id'          => 41,
                'name'        => 'Filled100Requests',
                'description' => 'DAMN BRO! You have already filled 100 requests!',
                'points'      => 100,
                'secret'      => 0,
                'class_name'  => \App\Achievements\UserFilled100Requests::class,
                'created_at'  => '2017-08-28 23:55:56',
                'updated_at'  => '2017-08-28 23:55:56',
            ],
        ];
    }
}
