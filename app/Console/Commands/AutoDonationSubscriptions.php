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
     *
     * @return int
     */
    public function handle(): void
    {
        $curDate = Carbon::now();
        $vipsDemote = DonationSubscription::where('end_at', '<=', $curDate->toDateString())->where('is_active', '=', true)->get();
        $vipsPromote = DonationSubscription::where('start_at', '<=', $curDate->toDateString())->where('is_active', '=', false)->get();

        // Demote VIP User
        foreach ($vipsDemote as $vip) {
            // Find The User
            $user = User::findOrFail($vip->user_id);

            $user->is_donor = false;
            $user->save();

            $vip->is_active = false;
            $vip->save();

            // Send Private Message
            $pm = new PrivateMessage();
            $pm->sender_id = User::SYSTEM_USER_ID;
            $pm->receiver_id = $user->id;
            $pm->subject = 'VIP Subscription ended';
            $pm->message = 'Hi,
                            
                            Your VIP subscription has ended recently. Your Rank has been reset and your VIP advantages disabled. 
                            The system will move you to the appropriate group within the next hours. 

                            Thank you for your support!

                            [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
            $pm->save();
        }

        // Promote VIP User
        foreach ($vipsPromote as $vip) {
            // Find The User
            $user = User::findOrFail($vip->user_id);

            // Add Gifts
            $donationItem = DonationItem::find($vip->donation_item_id);
            $user->seedbonus += $donationItem->seedbonus ?? 0;
            $user->uploaded += $donationItem->uploaded ?? 0;
            $user->invites += $donationItem->invites ?? 0;

            // Set user as donor
            $user->is_donor = true;
            $user->save();

            // Update donation subscription table
            $vip->is_active = true;
            $vip->is_gifted = true;
            $vip->save();

            // Send Private Message
            $pm = new PrivateMessage();
            $pm->sender_id = User::SYSTEM_USER_ID;
            $pm->receiver_id = $user->id;
            $pm->subject = 'VIP Subscription';
            $pm->message = 'Hi,
                            
                            [b]Thank you for supporting '.config('app.name').'![/b]
                            Your VIP access has been activated and is valid through: '.$vip->end_at.' (YYYY-MM-DD)
                            A total of '.$donationItem->seedbonus.' BON points, '
                            .$donationItem->uploaded.' upload and '
                            .$donationItem->invites.' invites have been added to your account. 
                            
                            [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
            $pm->save();
        }
        $this->comment('Automated VIP Users Command Complete');
    }
}
