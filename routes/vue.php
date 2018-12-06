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
 * @author     Poppabear
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

        /* Messages */
        Route::post('/messages', 'ChatController@createMessage');
        Route::get('/message/{id}/delete', 'ChatController@deleteMessage');
        Route::get('/messages/{room_id}', 'ChatController@messages');

        /* Users */
        Route::post('/user/{id}/chatroom', 'ChatController@updateUserRoom');
        Route::post('/user/{id}/status', 'ChatController@updateUserChatStatus');
    });
});
