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

namespace App\Achievements;

use Assada\Achievements\Achievement;

class UserMade25Posts extends Achievement
{
    /**
     * The achievement name
     *
     * @var string
     */
    public $name = '25Posts';

    /**
     * A small description for the achievement
     *
     * @var string
     */
    public $description = 'Wow! You have already made 25 posts!';

    /**
     * The amount of "points" this user need to obtain in order to complete this achievement
     *
     * @var int
     */
    public $points = 25;
}
