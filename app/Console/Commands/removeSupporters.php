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

namespace App\Console\Commands;

use App\Donation;
use App\User;
use Carbon\Carbon;
use Cache;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class removeSupporters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'removeSupporters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Removes Supporters If Expired';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $current = Carbon::now();
        $donations = Donation::where('active', '=', 1)->get();

        foreach ($donations as $donation) {
            $supporter = Donation::where('user_id', '=', $donation->user_id)->where('created_at', '<', $current->copy()->subDays($donation->time)->toDateTimeString())->get();
            $supporter->active = 0;
            $supporter->save();

            $user = User::where('id', '=', $supporter->id)->get();
            $old_group = Group::where('name', '=', $donation->rank)->first();
            $user->group_id = $old_group->id;
            $user->save();

            PrivateMessage::create(['sender_id' => "1", 'reciever_id' => $user->id, 'subject' => "Supporter Group Expired", 'message' => "Your [b]Supporter Group[/b] has expired! Feel free to reenable it by making another donation. Thank you for your support! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]"]);
        }
    }
}
