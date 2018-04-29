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

use App\Chatroom;
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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    // this is saying if the id which is being asked to be broadcast to is the same
    // as the logged in user broadcast to this user.
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chatroom.{id}', function ($user, $id) {
    // firstly check the user is authorised.

    if (auth()->check()) {
        // if the user is authorised then go ahead and check if the user
        // belongs to the chatroom.
        // (you can only be in a chatroom at one time)

        $chatroom = Chatroom::findOrFail($id);

        if ($chatroom->users()
            ->get()
            ->contains($user)) {
            return true;
        }
    }

    return false;
});
