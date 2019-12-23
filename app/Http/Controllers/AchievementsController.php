<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use App\Models\User;
use Illuminate\Http\Request;

final class AchievementsController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;
    public function __construct(Factory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }
    /**
     * Display All Achievements.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request): Factory
    {
        $user = $request->user();

        $achievements = $user->unlockedAchievements();
        $pending = $user->inProgressAchievements();

        return $this->viewFactory->make('achievement.index', [
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
    public function show($username): Factory
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        $achievements = $user->unlockedAchievements();

        return $this->viewFactory->make('achievement.show', [
            'route'        => 'achievement',
            'user'         => $user,
            'achievements' => $achievements,
        ]);
    }
}
