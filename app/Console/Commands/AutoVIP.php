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
use App\Models\Group;
use App\Models\User;
use App\Models\PrivateMessage;
use Carbon\Carbon;
use DB;

class AutoVIP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:vip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove VIP rank for users.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $curDate = Carbon::now();
        $vip_group = Group::select(['id'])->where('slug', '=', 'vip')->first();
        $vipul_group = Group::select(['id'])->where('slug', '=', 'vip-ul')->first();
        $default_group = DB::table('groups')->select('id')->where('slug', '=', 'phobos')->first();
        $defaultul_group = DB::table('groups')->select('id')->where('slug', '=', 'uploader')->first();
        $vips_demote = DonationSubscription::where('end_at', '=', $curDate->toDateString())->where('is_active', '=', 1)->get();
        $vips_promote = DonationSubscription::where('start_at', '=', $curDate->toDateString())->where('is_active', '=', 0)->get();

        // Demote VIP User
        foreach ($vips_demote as $vip) {
            // Find The User
            $user = User::findOrFail($vip->user_id);
            
            // Default User or uploader
            if ($user->group_id == $vipul_group->id) {
                $user->update([
                    'group_id'  => $defaultul_group->id,
                ]);
            }
            else {
                $user->update([
                    'group_id'  => $default_group->id,
                ]);
            }
            
            $vip->is_active = 0;
            $vip->save();

            // Send Private Message
            $pm = new PrivateMessage();
            $pm->sender_id = 1;
            $pm->receiver_id = $user->id;
            $pm->subject = 'VIP Subscription ended';
            $pm->message = 'Hi,
                            
                            Your VIP subscription has ended recently. Your Rank has been reset and your VIP advantages disabled. 
                            The system will move you to the appropriate group within the next hours. 
                            If you consider extending your VIP membership, pls take a look at [url=/pages/donate]https://aither.cc/pages/donate[/url].
                            Thank you for supporting Aither.cc!
                            
                            ~ Your Aither Staff 
                            
                            [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
            $pm->save();
        }

        // Sleep in between sending those messages
        sleep(2);

        // Promote VIP User
        foreach ($vips_promote as $vip) {
            // Find The User
            $user = User::findOrFail($vip->user_id);
                
            // Default User or uploader
            if ($user->group_id == $defaultul_group->id) {
                $user->update([
                    'group_id'  => $vipul_group->id,
                ]);
            }
            else {
                $user->update([
                    'group_id'  => $vip_group->id,
                ]);
            }

            // Add Gifts
            $donationItem = DonationItem::find($vip->item_id);
            if ($donationItem->bon_bonus) {
                $user->seedbonus += $donationItem->bon_bonus;
            }
            if ($donationItem->ul_bonus) {
                $user->uploaded += $donationItem->ul_bonus;
            }
            if ($donationItem->invite_bonus) {
                $user->invites += $donationItem->invite_bonus;
            }
            if ($donationItem->days_active >= 180 ) {
                $s4me = true;
            }
            else {
                $s4me = false;
            }

            $vip->is_gifted = 1;
            $vip->is_active = 1;
            $vip->save();
            $user->save();

            // Send Private Message
            $pm = new PrivateMessage();
            $pm->sender_id = 1;
            $pm->receiver_id = $user->id;
            $pm->subject = 'VIP Subscription';
            $pm->message = 'Hi,
                            
                            [b]Thank you for supporting Aither![/b]
                            Your VIP access has been activated and is valid through: '.$vip->end_at.' (YYYY-MM-DD)
                            A total of '.$donationItem->bon_bonus.' BON points have been added to your account. '.
                            ($s4me == true ? 'Your Seedit4Me discount code is: ACC10':'')
                            .'
                            
                            ~ Your Aither Staff 
                            
                            [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
            $pm->save();
        }
        $this->comment('Automated VIP Users Command Complete');
    }
}
