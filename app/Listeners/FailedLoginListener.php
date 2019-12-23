<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Listeners;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\FailedLoginAttempt;
use App\Notifications\FailedLogin;
use Illuminate\Support\Facades\Request;

final class FailedLoginListener
{
    /**
     * @var \Illuminate\Http\Request
     */
    private $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Handle the event.
     *
     * @param  auth.failed  $event
     * @return void
     */
    public function handle($event): void
    {
        FailedLoginAttempt::record(
            $event->user,
            $this->request->input('username'),
            $this->request->getClientIp()
        );

        if (isset($event->user) && is_a($event->user, Model::class)) {
            $event->user->notify(new FailedLogin(
                $this->request->getClientIp()
            ));
        }
    }
}
