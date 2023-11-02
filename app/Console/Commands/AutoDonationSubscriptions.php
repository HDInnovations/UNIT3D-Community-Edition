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
        $toDemote = DonationSubscription::with('user', 'item')->where('end_at', '<=', $curDate->toDateString())->where('is_active', '=', true)->get();
        $toPromote = DonationSubscription::with('user', 'item')->where('start_at', '<=', $curDate->toDateString())->where('is_active', '=', false)->get();

        // Demote a User
        User::whereIntegerInRaw('id', $toDemote->pluck('user_id'))->update(['is_donor' => false]);
        DonationSubscription::whereIntegerInRaw('id', $toDemote->pluck('id'))->update(['is_active' => false]);

        foreach ($toDemote as $subscription) {
            PrivateMessage::create([
                'sender_id'   => User::SYSTEM_USER_ID,
                'receiver_id' => $subscription->user->id,
                'subject'     => 'Donation Subscription ended',
                'message'     => 'Your subscription ('.$subscription->item->name.') has ended recently and your donation perks have been disabled. 

Thank you for your support!

[color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]',
            ]);
        }

        // Promote a User
        foreach ($toPromote as $subscription) {
            dd($subscription->item->name);
            // Add Gifts
            $subscription->user->seedbonus += $subscription->item->seedbonus ?? 0;
            $subscription->user->uploaded += $subscription->item->uploaded ?? 0;
            $subscription->user->invites += $subscription->item->invites ?? 0;

            // Set user as donor (and grant freeleech) if item has "days_active"
            if ($subscription->item->days_active > 0) {
                $user->is_donor = true;
                $user->save();
            }

            // Update donation subscription table
            $subscription->is_active = true;
            $subscription->is_gifted = true;
            $subscription->save();

            // Send Private Message
            PrivateMessage::create([
                'sender_id'   => User::SYSTEM_USER_ID,
                'receiver_id' => $user->id,
                'subject'     => 'Donation Subscription',
                'message'     => '[b]Thank you for supporting '.config('app.name').'![/b]'."\n"
.'Your subscription access has been activated and is valid through: '.$subscription->end_at.' (YYYY-MM-DD)'."\n\n"
.'A total of '.$subscription->item->seedbonus.' BON points, '.$subscription->item->uploaded.' upload and '.$subscription->item->invites.' invites have been added to your account.'."\n\n"
.'[color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]',
            ]);
        }
        $this->comment('Automated VIP Users Command Complete');
    }
}
