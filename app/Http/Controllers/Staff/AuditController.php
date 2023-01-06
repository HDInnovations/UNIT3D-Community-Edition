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
        $audits = Audit::with('user')->latest()->paginate(50);

        foreach ($audits as $audit) {
            $audit->values = json_decode($audit->record, true, 512, JSON_THROW_ON_ERROR);
        }

        return \view('Staff.audit.index', ['audits' => $audits]);
    }

    /**
     * Delete A Audit.
     *
     * @throws \Exception
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        Audit::findOrFail($id)->delete();

        return \to_route('staff.audits.index')
            ->withSuccess('Audit Record Has Successfully Been Deleted');
    }
}
