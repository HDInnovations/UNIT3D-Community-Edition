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
use App\Models\DonationSubscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\UserControllerTest
 */
class VIPController extends Controller
{
    /**
     * Display All VIPs.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_modo, 403);

        $curDate = Carbon::now();

        $vips_upcoming = DonationSubscription::with('user')
            ->where('start_at', '>=', $curDate->toDateString())
            ->where('end_at', '>', $curDate->toDateString())
            ->orderBy('start_at')
            ->paginate(5);
        $vips_active = DonationSubscription::with('user')
            ->where('start_at', '<', $curDate->toDateString())
            ->where('end_at', '>=', $curDate->toDateString())
            ->orderBy('end_at')
            ->paginate(25);
        $vips_inactive = DonationSubscription::with('user')
            ->where('end_at', '<', $curDate->toDateString())
            ->orderBy('end_at', 'desc')
            ->paginate(10);
        $vips_active_arr = DonationSubscription::with('user')
            ->where('start_at', '<', $curDate->toDateString())
            ->where('end_at', '>=', $curDate->toDateString())
            ->orderBy('end_at')
            ->pluck('user_id')
            ->toArray();

        return view('Staff.vips.index', [
            'vips_active'     => $vips_active,
            'vips_upcoming'   => $vips_upcoming,
            'vips_inactive'   => $vips_inactive,
            'vips_active_arr' => $vips_active_arr,
            'curdate'         => $curDate
        ]);
    }

    /**
     * Edit A VIP Subscription.
     */
    public function edit(Request $request, int $id)
    {
        $user = $request->user();
        abort_unless($user->group->is_admin, 403);

        $curDate = Carbon::now();
        $vip_sub = DonationSubscription::findOrFail($id);

        return view('Staff.vips.edit', [
            'vip_sub' => $vip_sub,
            'curdate' => $curDate,
            'date'    => $curDate
        ]);
    }

    /**
     * Save a VIP Subscription change.
     */
    public function update(Request $request, int $id)
    {
        $user = $request->user();
        abort_unless($user->group->is_admin, 403);

        $curDate = Carbon::now();
        $vip_sub_end_at_old = "";
        $vip_sub = DonationSubscription::findOrFail($id);

        // Set Dates from Input form
        $vip_sub->start_at = $request->input('start_at');
        $vip_sub->end_at = $request->input('end_at');

        $v = validator($vip_sub->toArray(), [
            'start_at' => 'required|date',
            'end_at'   => 'required|date|after:start_at',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.vips.index')
                ->withErrors($v->errors());
        }
        $vip_sub->save();

        return redirect()->route('staff.vips.index')
            ->withSuccess('VIP Subscription Was Updated Successfully!');
    }

    /**
     * VIP Add Form.
     */
    public function create()
    {
        return view('Staff.vips.create');
    }

    /**
     * Store A New VIP.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_admin, 403);

        $vip = new DonationSubscription();
        $vipUsername = $request->input('username');
        // Get Users ID to store FK in DB
        $vip->user_id = User::query()
            ->select(['id'])
            ->where('username', '=', $vipUsername)
            ->value('id');
        $vip->start_at = $request->input('start_at');
        $vip->end_at = $request->input('end_at');
        $vip->donation_item_id = $request->input('donation_item_id');
        $vip->is_gifted = 0;
        $vip->is_active = 0;

        $v = validator($vip->toArray(), [
            'user_id'  => 'required',
            'start_at' => 'required|date',
            'end_at'   => 'required|date',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.vips.index')
                ->withErrors($v->errors());
        }
        $vip->save();

        return redirect()->route('staff.vips.index')
            ->withSuccess('New VIP Subscription added!');
    }
}
