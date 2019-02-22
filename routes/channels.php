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

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
 * |--------------------------------------------------------------------------
 * | Broadcast Channels
 * |--------------------------------------------------------------------------
 * |
 * | Here you may register all of the event broadcasting channels that your
 * | application supports. The given channel authorization callbacks are
 * | used to check if an authenticated user can listen to the channel.
 * |
 */

Broadcast::channel('chatroom.{id}', function ($user, $id) {
    return User::with([
        'chatStatus',
        'chatroom',
        'echoes',
        'torrents',
        'echoes.target',
        'echoes.room',
        'group',
    ])->find($user->id);
});
Broadcast::channel('chatter.{id}', function ($user, $id) {
    return $user->id == $id;
});
