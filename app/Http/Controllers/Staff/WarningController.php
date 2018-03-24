<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Warning;

class WarningController extends Controller
{
    /**
     * Warnings Log
     *
     *
     */
    public function getWarnings()
    {
        $warnings = Warning::with(['torrenttitle', 'warneduser'])->latest()->paginate(25);
        $warningcount = Warning::where('active', 1)->count();

        return view('Staff.warnings.index', ['warnings' => $warnings, 'warningcount' => $warningcount]);
    }
}
