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
class DonationSubscriptionController extends Controller
{
    /**
     * Display All VIPs.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->group->is_modo, 403);

        $currentDate = Carbon::now()->toDateString();

        return view('Staff.donations.subscriptions.index', [
            'subscriptions_active'     => DonationSubscription::with('user')
                ->where('is_active', '=', true)
                ->paginate(25),
            'subscriptions_upcoming'   => DonationSubscription::with('user')
                ->where('start_at', '>=', $currentDate)
                ->where('is_active', '=', false)
                ->where('end_at', '>', $currentDate)
                ->orderBy('start_at')
                ->paginate(5),
            'subscriptions_inactive'   => DonationSubscription::with('user')
                ->where('end_at', '<=', $currentDate)
                ->where('is_active', '=', false)
                ->orderBy('end_at', 'desc')
                ->paginate(10),
            'subscriptions_active_arr' => DonationSubscription::query()
                ->whereRelation('user', fn ($query) => $query->where('is_donor', '=', true))
                ->where('is_donor', '=', true)
                ->where('start_at', '<=', $currentDate)
                ->where('end_at', '>=', $currentDate)
                ->pluck('user_id')
                ->toArray(),
            'curdate'                  => $currentDate
        ]);
    }

    /**
     * Edit A VIP Subscription.
     */
    public function edit(Request $request, DonationSubscription $donationSubscription): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.donations.subscriptions.edit', [
            'vip_sub' => $donationSubscription,
        ]);
    }

    /**
     * Save a VIP Subscription change.
     */
    public function update(Request $request, DonationSubscription $donationSubscription): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'start_at' => [
                'required',
                'date',
            ],
            'end_at' => [
                'required',
                'date',
                'after:start_at',
            ]
        ])
        
        $donationSubscription->update($validated);

        return redirect()->route('staff.donations.subscriptions.index')
            ->withSuccess('VIP Subscription Was Updated Successfully!');
    }

    /**
     * VIP Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.donations.subscriptions.create');
    }

    /**
     * Store A New VIP.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $userId = User::query()
            ->select(['id'])
            ->where('username', '=', $request->username)
            ->value('id');

        $validated = $request->validate([
            'donation_item_id' => [
                'exists:donation_items,id',
            ],
            'username'  => [
                'exists:users,username',
                'exclude',
            ],
            'start_at' => [
                'required',
                'date',
            ],
            'end_at'   => [
                'required',
                'date'
            ],
        ]);
        
        DonationSubscription::create([
            'user_id'   => $userId,
            'is_gifted' => false,
            'is_active' => false,
        ] + $validated));

        return to_route('staff.donations.subscriptions.index')
            ->withSuccess('New VIP Subscription added!');
    }
}
