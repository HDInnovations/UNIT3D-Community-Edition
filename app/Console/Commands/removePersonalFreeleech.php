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

use Illuminate\Console\Command;
use App\PrivateMessage;
use App\PersonalFreeleech;
use Carbon\Carbon;

class removePersonalFreeleech extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'removePersonalFreeleech';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Removes A Users Personal Freeleech If It Has Expired';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $current = Carbon::now();
        $personal_freeleech = PersonalFreeleech::where('created_at', '<', $current->copy()->subDays(1)->toDateTimeString())->get();

        foreach ($personal_freeleech as $pfl) {
            // Send Private Message
            $pm = new PrivateMessage;
            $pm->sender_id = 1;
            $pm->receiver_id = $pfl->user_id;
            $pm->subject = "Personal 24 Hour Freeleech";
            $pm->message = "Your [b]Personal 24 Hour Freeleech[/b] has expired! Feel free to reenable it in the BON Store! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]";
            $pm->save();

            // Delete The Record From DB
            $pfl->delete();
        }
    }
}
