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

namespace App\Http\Controllers;

use App\User;

class AchievementsController extends Controller
{

    /**
     * Show User Achievements
     *
     * @access public
     * @return user.achievements
     */
    public function index($username, $id)
    {
        $user = User::findOrFail($id);
        $achievements = $user->unlockedAchievements();
        $locked = $user->lockedAchievements();
        $pending = $user->inProgressAchievements();

        return view('user.achievements', ['user' => $user, 'achievements' => $achievements, 'locked' => $locked, 'pending' => $pending]);
    }

}
