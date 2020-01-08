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
use App\Models\Audit;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AuditController extends Controller
{
    /**
     * Display All Audits.
     *
     * @return Factory|View
     */
    public function index()
    {
        $audits = Audit::with('user')->latest()->paginate(50);

        return view('Staff.audit.index', ['audits' => $audits]);
    }

    /**
     * Delete A Audit.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $audit = Audit::findOrFail($id);

        abort_unless($user->group->is_modo, 403);
        $audit->delete();

        return redirect()->route('staff.audits.index')
            ->withSuccess('Audit Record Has Successfully Been Deleted');
    }
}
