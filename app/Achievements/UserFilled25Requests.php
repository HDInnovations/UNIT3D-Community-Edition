<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Achievements;

use Gstt\Achievements\Achievement;

class UserFilled25Requests extends Achievement
{
    /*
     * The achievement name
     */
    public $name = "Filled25Requests";

    /*
     * A small description for the achievement
     */
    public $description = "Congrats! You have already filled 25 requests!";

    /*
    * The amount of "points" this user need to obtain in order to complete this achievement
    */
    public $points = 25;
}
