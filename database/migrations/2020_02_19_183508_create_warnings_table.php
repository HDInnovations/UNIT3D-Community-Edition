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

class CreateWarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warnings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('warned_by');
            $table->unsignedBigInteger('torrent');
            $table->text('reason');
            $table->dateTime('expires_on')->nullable();
            $table->boolean('active')->default(0);
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->nullableTimestamps();
        });

        Schema::table('warnings', function (Blueprint $table) {
            $table->index('warned_by', 'warnings_warned_by_foreign');
            $table->index('user_id', 'warnings_user_id_foreign');
            $table->index('torrent', 'warnings_torrent_foreign');

            $table->foreign('torrent')->references('id')->on('torrents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('warned_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warnings');
    }
}