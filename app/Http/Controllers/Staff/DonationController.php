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

use App\Models\Conversation;
use App\Services\Unit3dAnnounce;
use Carbon\Carbon;
use App\Models\Donation;
use Illuminate\Http\Request;
use App\Models\PrivateMessage;
use App\Http\Controllers\Controller;

class DonationController extends Controller
{
    /**
     * Get All Donations.
     */
    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        abort_unless($request->user()->group->is_owner, 403);

        return view('Staff.donation.index', ['donations' => Donation::with('package')->latest()->paginate(25)]);
    }

    /**
     * Update A Donation.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_owner, 403);

        $now = Carbon::now();

        $donation = Donation::with(['user', 'package'])->findOrFail($id);
        $donation->status = Donation::APPROVED;
        $donation->starts_at = $now;

        if ($donation->package->donor_value > 0) {
            $donation->ends_at = $now->addDays($donation->package->donor_value);
        } else {
            $donation->ends_at = null;
        }

        $donation->user->invites += $donation->package->invite_value;
        $donation->user->uploaded += $donation->package->upload_value;
        $donation->user->is_donor = true;
        $donation->user->is_lifetime = $donation->package->donor_value === null;
        $donation->user->seedbonus += $donation->package->bonus_value;
        $donation->user->save();

        $conversation = Conversation::create(['subject' => 'Your donation from '.$donation->created_at.', has been approved by '.$request->user()->username]);
        $conversation->users()->sync([$request->user()->id => ['read' => true], $donation->user_id]);

        PrivateMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $request->user()->id,
            'message'         => '[b]Thank you for supporting '.config('app.name').'[/b]'."\n"
                .'Your donation has been approved and is valid through: '.$donation->ends_at ?? 'Lifetime'.' (YYYY-MM-DD)'."\n"
                .'A total of '.$donation->package->bonus_value.' BON points, '.$donation->package->upload_value.' upload and '.$donation->package->invite_value,
        ]);

        $donation->save();

        cache()->forget('user:'.$donation->user->passkey);
        Unit3dAnnounce::addUser($donation->user);

        return redirect()->route('staff.donations.index')
            ->withSuccess('Donation Approved!');
    }

    /**
     * Destroy A Donation.
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_owner, 403);

        $donation = Donation::findOrFail($id);
        $donation->status = Donation::REJECTED;

        $conversation = Conversation::create(['subject' => 'Your donation from '.$donation->created_at.', has been rejected by '.$request->user()->username]);
        $conversation->users()->sync([$request->user()->id => ['read' => true], $donation->user_id]);

        PrivateMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $request->user()->id,
            'message'         => 'Your donation could not be approved at this time. Please contact us for more information by replying to this private message.',
        ]);

        $donation->save();

        return redirect()->route('staff.donations.index')
            ->withSuccess('Donation Rejected!');
    }
}
