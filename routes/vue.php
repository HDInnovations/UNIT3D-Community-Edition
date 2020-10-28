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

/*
|--------------------------------------------------------------------------
| API Routes For Vue Components
|--------------------------------------------------------------------------
*/

Route::namespace('API')->group(function () {
    Route::prefix('chat')->group(function () {
        Route::get('/config', 'ChatController@config');

        /* Statuses */
        Route::get('/statuses', 'ChatController@statuses');

        /* Rooms */
        Route::get('/rooms', 'ChatController@rooms');

        /* Bots */
        Route::get('/bots', 'ChatController@bots');

        /* Audibles */
        Route::get('/audibles', 'ChatController@audibles');
        Route::post('/audibles/{user_id}/toggle/chatroom', 'ChatController@toggleRoomAudible');
        Route::post('/audibles/{user_id}/toggle/target', 'ChatController@toggleTargetAudible');
        Route::post('/audibles/{user_id}/toggle/bot', 'ChatController@toggleBotAudible');

        /* Echoes */
        Route::get('/echoes', 'ChatController@echoes');
        Route::post('/echoes/{user_id}/delete/chatroom', 'ChatController@deleteRoomEcho');
        Route::post('/echoes/{user_id}/delete/target', 'ChatController@deleteTargetEcho');
        Route::post('/echoes/{user_id}/delete/bot', 'ChatController@deleteBotEcho');

        /* Messages */
        Route::post('/messages', 'ChatController@createMessage');
        Route::get('/message/{id}/delete', 'ChatController@deleteMessage');
        Route::get('/messages/{room_id}', 'ChatController@messages');

        /* Private Stuff */
        Route::get('/private/messages/{target_id}', 'ChatController@privateMessages');

        /* Bot Stuff */
        Route::get('/bot/{bot_id}', 'ChatController@botMessages');

        /* Users */
        Route::post('/user/{id}/target', 'ChatController@updateUserTarget');
        Route::post('/user/{id}/chatroom', 'ChatController@updateUserRoom');
        Route::post('/user/{id}/status', 'ChatController@updateUserChatStatus');
    });
});
