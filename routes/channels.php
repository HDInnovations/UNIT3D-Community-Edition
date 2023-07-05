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

use App\Models\User;
use App\Models\UserEcho;
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

Broadcast::channel('messages.room.{chatroomId}', function (User $user, int $chatroomId) {
    if (
        UserEcho::query()
            ->where('user_id', '=', $user->id)
            ->where('room_id', '=', $chatroomId)
            ->exists()
    ) {
        return User::select([
            'id',
            'username',
            'group_id',
            'image',
            'chatroom_id',
            'chat_status_id'
        ])
            ->find($user->id);
    }

    return false;
});
Broadcast::channel('messages.pm.{user1Id}-{user2Id}', function (User $user, int $user1Id, int $user2Id) {
    if (!($user->id === $user1Id || $user->id === $user2Id)) {
        return false;
    }

    if (
        UserEcho::query()
            ->where(fn ($query) => $query->where('user_id', '=', $user1Id)->where('target_id', '=', $user2Id))
            ->orWhere(fn ($query) => $query->where('user_id', '=', $user2Id)->where('target_id', '=', $user1Id))
            ->exists()
    ) {
        return User::select([
            'id',
            'username',
            'group_id',
            'image',
            'chatroom_id',
            'chat_status_id'
        ])
            ->find($user->id);
    }

    return false;
});

Broadcast::channel('echo.created.{userId}', fn (User $user, int $userId) => $user->id === $userId);
