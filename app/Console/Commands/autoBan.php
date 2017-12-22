<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     Mr.G
 */

namespace App\Console\Commands;

use App\Mail\BanUser;
use App\Warning;
use App\User;
use App\Ban;

use Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class autoBan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoBan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ban if user has more than x Active Warnings';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bans = Warning::with('warneduser')->select(DB::raw('user_id, count(*) as value'))->where('active', '=', '1')->groupBy('user_id')->having('value', '>=', config('hitrun.buffer'))->get();

        foreach ($bans as $ban) {
            if ($ban->warneduser->group_id != 5 && !$ban->warneduser->group->is_immune) {
                // If User Has x or More Active Warnings Ban Set The Users Group To Banned
                $ban->warneduser->group_id = 5;
                $ban->warneduser->save();

                // Log The Ban To Ban Log
                $ban = new Ban([
                    "owned_by" => $ban->warneduser->id,
                    "created_by" => "1",
                    "ban_reason" => "Warning Limit Reached, has " . $ban->value . " warnings.",
                    "unban_reason" => "",
                ]);
                $ban->save();
            }
        }
    }
}
