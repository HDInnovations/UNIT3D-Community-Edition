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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('peer_id', 60)->nullable();
            $table->string('md5_peer_id')->nullable();
            $table->string('info_hash')->nullable();
            $table->string('ip')->nullable();
            $table->unsignedSmallInteger('port')->nullable();
            $table->string('agent')->nullable();
            $table->unsignedBigInteger('uploaded')->nullable();
            $table->unsignedBigInteger('downloaded')->nullable();
            $table->unsignedBigInteger('left')->nullable();
            $table->boolean('seeder')->nullable();
            $table->nullableTimestamps();
            $table->unsignedBigInteger('torrent_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::table('peers', function (Blueprint $table) {
            $table->index('user_id', 'fk_peers_users1_idx');
            $table->index('torrent_id', 'fk_peers_torrents1_idx');

            $table->foreign('torrent_id')->references('id')->on('torrents');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peers');
    }
}
