<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Listeners;

use App\Models\FailedLoginAttempt;
use App\Notifications\FailedLogin;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Request;

class RecordFailedLoginAttempt
{
    public function handle(Failed $event)
    {
        FailedLoginAttempt::record(
            $event->user,
            Request::input('username'),
            Request::getClientIp()
        );

        if (isset($event->user) && is_a($event->user, 'Illuminate\Database\Eloquent\Model')) {
            $event->user->notify(new FailedLogin(
                Request::getClientIp()
            ));
        }
    }
}
