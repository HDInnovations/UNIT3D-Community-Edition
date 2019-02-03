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

namespace App\Helpers;

use App\LogActivity as LogActivityModel;

class LogActivity
{
    public static function addToLog($subject)
    {
        $user = auth()->user();
        if ($user) {
            $log = new LogActivityModel();
            $log->subject = $subject;
            $log->url = request()->fullUrl();
            $log->method = request()->method();
            $log->ip = $user->group->is_incognito ? '0.0.0.0' : request()->ip();
            $log->agent = $user->group->is_incognito ? 'Unknown' : request()->header('user-agent');
            $log->user_id = auth()->check() ? $user->id : 0;
            $log->save();
        }
    }
}
