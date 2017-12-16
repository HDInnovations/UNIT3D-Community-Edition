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

class UserMadeFirstPost extends Achievement
{
    /*
     * The achievement name
     */
    public $name = "FirstPost";

    /*
     * A small description for the achievement
     */
    public $description = "Congratulations! You have made your first post!";
}
