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
use App\Models\Warning;

class WarningController extends Controller
{
    /**
     * Warnings Log.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $warnings = Warning::with(['torrenttitle', 'warneduser'])->latest()->paginate(25);
        $warningcount = Warning::count();

        return view('Staff.warning.index', ['warnings' => $warnings, 'warningcount' => $warningcount]);
    }
}
