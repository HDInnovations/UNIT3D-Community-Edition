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

namespace App\Listeners;

use App\Models\FailedLoginAttempt;
use App\Notifications\FailedLogin;
use Illuminate\Support\Facades\Request;

class FailedLoginListener
{
    /**
     * Handle the event.
     *
     * @param auth.failed $event
     *
     * @return void
     */
    public function handle($event)
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
