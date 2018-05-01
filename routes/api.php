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

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace('API')->group(function () {

    Route::prefix('chat')->group(function () {
        /* MAIN ENDPOINT */
        Route::get('/room/{id}', 'ChatController@chat');

        /* Messages From Room */
        Route::get('/room/{id}/messages', 'ChatController@roomMessages');

        /* Rooms */
        Route::get('/rooms', 'ChatController@rooms');
        Route::post('/rooms', 'ChatController@createRoom');
        Route::put('/rooms/{id}', 'ChatController@updateRoom');
        Route::delete('/rooms/{id}', 'ChatController@destroyRoom');

        /* Messages */
        Route::get('/messages', 'ChatController@messages');
        Route::post('/messages', 'ChatController@createMessage');
        Route::put('/message/{id}', 'ChatController@updateMessage');
        Route::delete('/messages/{id}', 'ChatController@destroyRoom');

        /* Users */
        Route::get('/user/{id}/chatroom', 'ChatController@userRoom');
        Route::put('/user/{id}/chatroom', 'ChatController@updateUserRoom');
    });

});