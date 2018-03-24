<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PrivateMessage;
use App\Warning;
use Carbon\Carbon;

class deactivateWarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deactivateWarning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Deactivates User Warnings If Expired';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $current = Carbon::now();
        $warnings = Warning::with(['warneduser', 'torrenttitle'])->where('active', 1)->where('expires_on', '<', $current)->get();

        foreach ($warnings as $warning) {
            // Set Records Active To 0 in warnings table
            $warning->active = "0";
            $warning->save();

            // PM User That Warning Has Expired
            PrivateMessage::create(['sender_id' => "1", 'reciever_id' => $warning->warneduser->id, 'subject' => "Hit and Run Warning Deactivated", 'message' => "The [b]WARNING[/b] you received relating to Torrent " . $warning->torrenttitle->name . " has expired! Try not to get more! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]"]);
        }
    }
}
