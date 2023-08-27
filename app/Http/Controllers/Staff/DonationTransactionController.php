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
use App\Models\DonationTransaction;
use Illuminate\Http\Request;

class DonationTransactionController extends Controller
{
    /**
     * Display All Transactions.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        abort_unless($user->group->is_modo, 403);

        $transactions = DonationTransaction::with(['user','item'])->orderBy('created_at')->get();

        return view('Staff.donations.transactions.index', [
            'transactions' => $transactions
        ]);
    }
}
