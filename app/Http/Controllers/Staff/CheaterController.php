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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\DatabaseManager;

final class CheaterController extends Controller
{
    /**
     * @var \Illuminate\Database\DatabaseManager
     */
    private $databaseManager;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;

    public function __construct(DatabaseManager $databaseManager, Factory $viewFactory)
    {
        $this->databaseManager = $databaseManager;
        $this->viewFactory = $viewFactory;
    }

    /**
     * Possible Ghost Leech Cheaters.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): Factory
    {
        $cheaters = History::with('user')
            ->select(['*'])
            ->join(
                $this->databaseManager->raw('(SELECT MAX(id) AS id FROM history GROUP BY history.user_id) AS unique_history'),
                function ($join) {
                    $join->on('history.id', '=', 'unique_history.id');
                }
            )
            ->where('seeder', '=', 0)
            ->where('active', '=', 0)
            ->where('seedtime', '=', 0)
            ->where('actual_downloaded', '=', 0)
            ->where('actual_uploaded', '=', 0)
            ->whereNull('completed_at')
            ->latest()
            ->paginate(25);

        return $this->viewFactory->make('Staff.cheater.index', ['cheaters' => $cheaters]);
    }
}
