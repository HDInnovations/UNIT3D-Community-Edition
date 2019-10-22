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

use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    /**
     * Display All Achievements.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $achievements = $user->unlockedAchievements();
        $pending = $user->inProgressAchievements();

        return view('achievement.index', [
            'route'        => 'achievement',
            'user'         => $user,
            'achievements' => $achievements,
            'pending'      => $pending,
        ]);
    }

    /**
     * Show A Users Achievements.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $achievements = $user->unlockedAchievements();

        return view('achievement.show', [
            'route'        => 'achievement',
            'user'         => $user,
            'achievements' => $achievements,
        ]);
    }
}
