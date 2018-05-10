<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
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

        /* Statuses */
        Route::get('/statuses', 'ChatController@statuses');

        /* Rooms */
        Route::get('/rooms', 'ChatController@rooms');
        Route::get('/rooms/{room_id}/limits', 'ChatController@roomLimits');

        /* Messages */
        Route::post('/messages', 'ChatController@createMessage');

        /* Users */
        Route::post('/user/{id}/chatroom', 'ChatController@updateUserRoom');
        Route::post('/user/{id}/status', 'ChatController@updateUserChatStatus');
    });

});