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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\AuditControllerTest
 */
class AuditController extends Controller
{
    /**
     * Display All Audits.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.audit.index', ['staffActivities' => Audit::with(['user', 'user.group'])
            ->whereHas('user.group', function ($query): void {
                $query->where('is_modo', true);
            })
            ->where('action', '!=', 'create') // Exclude audits with action 'create'
            ->select('user_id')
            ->selectRaw('COUNT(*) as total_actions')
            ->selectRaw('SUM(CASE WHEN created_at > NOW() - INTERVAL 60 DAY THEN 1 ELSE 0 END) as last_60_days')
            ->selectRaw('SUM(CASE WHEN created_at > NOW() - INTERVAL 30 DAY THEN 1 ELSE 0 END) as last_30_days')
            ->groupBy('user_id')
            ->get()]);
    }

    /**
     * Delete A Audit.
     *
     * @throws Exception
     */
    public function destroy(Audit $audit): \Illuminate\Http\RedirectResponse
    {
        $audit->delete();

        return to_route('staff.audits.index')
            ->withSuccess('Audit Record Has Successfully Been Deleted');
    }
}
