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

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DonationItem;
use App\Models\DonationSubscription;
use App\Models\User;
use App\Models\PrivateMessage;
use Carbon\Carbon;

class AutoDonationSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:donation_subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add/Remove donor status for users.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $curDate = Carbon::now();
        $toDemote = DonationSubscription::with('user', 'donation_item')->where('end_at', '<=', $curDate->toDateString())->where('is_active', '=', true)->get();
        $toPromote = DonationSubscription::with('user', 'donation_item')->where('start_at', '<=', $curDate->toDateString())->where('is_active', '=', false)->get();

        // Demote a User
        User::whereIntegerInRaw('id', $toDemote->pluck('user_id'))->update(['is_donor' => false]);
        DonationSubscription::whereIntegerInRaw('id', $toDemote->pluck('id'))->update(['is_active' => false])
        
        foreach ($toDemote as $subscription) {
            PrivateMessage::create([
                'sender_id'   => User::SYSTEM_USER_ID,
                'receiver_id' => $subscription->user_id,
                'subject'     => 'Donation Subscription ended',
                'message'     => 'Your subscription ('.$subscription->donation_item->name.') has ended recently and your donation perks have been disabled. 

Thank you for your support!

[color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]',
            ]);
        }

        // Promote a User
        foreach ($toPromote as $donor) {
            $user = User::findOrFail($donor->user_id);
            $donationItem = DonationItem::find($donor->donation_item_id);

            // Add Gifts
            $user->seedbonus += $donationItem->seedbonus ?? 0;
            $user->uploaded += $donationItem->uploaded ?? 0;
            $user->invites += $donationItem->invites ?? 0;

            // Set user as donor (and grant freeleech) if item has "days_active"
            if ($donationItem->days_active > 0) {
                $user->is_donor = true;
                $user->save();
            }

            // Update donation subscription table
            $donor->is_active = true;
            $donor->is_gifted = true;
            $donor->save();

            // Send Private Message
            PrivateMessage::create([
                'sender_id'   => User::SYSTEM_USER_ID,
                'receiver_id' => $user->id,
                'subject'     => 'Donation Subscription',
                'message'     => '[b]Thank you for supporting '.config('app.name').'![/b]
                                  Your subscription access has been activated and is valid through: '.$donor->end_at.' (YYYY-MM-DD)
                                  A total of '.$donationItem->seedbonus.' BON points, '
                                  .$donationItem->uploaded.' upload and '
                                  .$donationItem->invites.' invites have been added to your account. 

                                  [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]',
            ]);
        }
        $this->comment('Automated VIP Users Command Complete');
    }
}
