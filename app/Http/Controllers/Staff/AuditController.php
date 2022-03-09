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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use Illuminate\Http\Request;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\AuditControllerTest
 */
class AuditController extends Controller
{
    /**
     * Display All Audits.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        \abort_unless($user->hasPrivilegeTo('dashboard_can_audit_log'), 403);
        $audits = Audit::with('user')->latest()->paginate(50);

        return \view('Staff.audit.index', ['audits' => $audits]);
    }

    /**
     * Delete A Audit.
     *
     * @throws \Exception
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $audit = Audit::findOrFail($id);

        \abort_unless($user->hasPrivilegeTo('dashboard_can_audit_log'), 403);
        $audit->delete();

        return \to_route('staff.audits.index')
            ->withSuccess('Audit Record Has Successfully Been Deleted');
    }
}
