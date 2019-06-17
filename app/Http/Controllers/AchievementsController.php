<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    /**
     * Show User Achievements.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $achievements = $user->unlockedAchievements();
        $locked = $user->lockedAchievements();
        $pending = $user->inProgressAchievements();

        return view('user.private.achievements', [
            'route'        => 'achievement',
            'user'         => $user,
            'achievements' => $achievements,
            'locked'       => $locked,
            'pending'      => $pending,
        ]);
    }
}
