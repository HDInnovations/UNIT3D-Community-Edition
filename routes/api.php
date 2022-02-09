<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/*
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

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
if (config('unit3d.proxy_scheme')) {
    URL::forceScheme(config('unit3d.proxy_scheme'));
}
if (config('unit3d.root_url_override')) {
    URL::forceRootUrl(config('unit3d.root_url_override'));
}
// Torrents System
Route::group(['middleware' => ['auth:api', 'banned'], 'prefix' => 'torrents'], function () {
    Route::get('/', [App\Http\Controllers\API\TorrentController::class, 'index'])->name('torrents.index');
    Route::get('/filter', [App\Http\Controllers\API\TorrentController::class, 'filter']);
    Route::get('/{id}', [App\Http\Controllers\API\TorrentController::class, 'show'])->where('id', '[0-9]+');
    Route::post('/upload', [App\Http\Controllers\API\TorrentController::class, 'store']);
});
