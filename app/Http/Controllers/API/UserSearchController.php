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

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserSearchController extends Controller
{
    public function index(Request $request)
    {
        if (!$q = $request->get('q', '')) {
            return response()->json([]);
        }

        return User::where(DB::raw('LOWER(username)'), 'LIKE', '%' . Str::lower($q) . '%')
            ->get(['id', 'username']);
    }
}
