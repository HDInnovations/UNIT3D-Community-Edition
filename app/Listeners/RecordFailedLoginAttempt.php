<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Listeners;

use Illuminate\Support\Facades\Request;
use Illuminate\Auth\Events\Failed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\FailedLoginAttempt;

class RecordFailedLoginAttempt
{
    public function handle(Failed $event)
    {
        FailedLoginAttempt::record(
            $event->user,
            Request::get('username'),
            Request::getClientIp()
        );
    }
}
