<?php

use Illuminate\Support\Facades\Route;

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

/*
|--------------------------------------------------------------------------
| API Routes For Vue Components
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'twostep', 'banned']], function () {
    Route::prefix('chat')->group(function () {
        Route::get('/config', [App\Http\Controllers\API\ChatController::class, 'config']);

        /* Statuses */
        Route::get('/statuses', [App\Http\Controllers\API\ChatController::class, 'statuses']);

        /* Rooms */
        Route::get('/rooms', [App\Http\Controllers\API\ChatController::class, 'rooms']);

        /* Bots */
        Route::get('/bots', [App\Http\Controllers\API\ChatController::class, 'bots']);

        /* Audibles */
        Route::get('/audibles', [App\Http\Controllers\API\ChatController::class, 'audibles']);
        Route::post('/audibles/{user_id}/toggle/chatroom', [App\Http\Controllers\API\ChatController::class, 'toggleRoomAudible']);
        Route::post('/audibles/{user_id}/toggle/target', [App\Http\Controllers\API\ChatController::class, 'toggleTargetAudible']);
        Route::post('/audibles/{user_id}/toggle/bot', [App\Http\Controllers\API\ChatController::class, 'toggleBotAudible']);

        /* Echoes */
        Route::get('/echoes', [App\Http\Controllers\API\ChatController::class, 'echoes']);
        Route::post('/echoes/{user_id}/delete/chatroom', [App\Http\Controllers\API\ChatController::class, 'deleteRoomEcho']);
        Route::post('/echoes/{user_id}/delete/target', [App\Http\Controllers\API\ChatController::class, 'deleteTargetEcho']);
        Route::post('/echoes/{user_id}/delete/bot', [App\Http\Controllers\API\ChatController::class, 'deleteBotEcho']);

        /* Messages */
        Route::post('/messages', [App\Http\Controllers\API\ChatController::class, 'createMessage']);
        Route::post('/message/{id}/delete', [App\Http\Controllers\API\ChatController::class, 'deleteMessage']);
        Route::get('/messages/{room_id}', [App\Http\Controllers\API\ChatController::class, 'messages']);

        /* Private Stuff */
        Route::get('/private/messages/{target_id}', [App\Http\Controllers\API\ChatController::class, 'privateMessages']);

        /* Bot Stuff */
        Route::get('/bot/{bot_id}', [App\Http\Controllers\API\ChatController::class, 'botMessages']);

        /* Users */
        Route::post('/user/{id}/target', [App\Http\Controllers\API\ChatController::class, 'updateUserTarget']);
        Route::post('/user/{id}/chatroom', [App\Http\Controllers\API\ChatController::class, 'updateUserRoom']);
        Route::post('/user/{id}/status', [App\Http\Controllers\API\ChatController::class, 'updateUserChatStatus']);
    });
});
