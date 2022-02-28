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

namespace App\Helpers;

use App\Models\User;

class CacheUser
{
    public static function user($id)
    {
        if (! $id || $id <= 0 || ! is_numeric($id)) {
            return;
        }

        return \cache()->remember('cachedUser.'.$id, 30, fn () => User::find($id));
    }
}
