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

use App\Models\Resolution;
use App\Models\Torrent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResolutionIdToTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('torrents', function (Blueprint $table) {
            $table->integer('resolution_id')->index();
        });

        if (Schema::hasColumn('torrents', 'resolution')) {
            foreach (Torrent::all() as $torrent) {
                $resolution_id = Resolution::where('name', '=', $torrent->resolution)->firstOrFail()->id;
                $torrent->resolution_id = $resolution_id;
                $torrent->save();
            }

            Schema::table('torrents', function (Blueprint $table) {
                $table->dropColumn('resolution');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('torrents', function (Blueprint $table) {
            //
        });
    }
}
