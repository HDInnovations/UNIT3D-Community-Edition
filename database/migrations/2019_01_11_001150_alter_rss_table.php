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

class AlterRssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('rss');
        Schema::create('rss', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('position')->default(0);
            $table->string('name', '255')->default('Default');
            $table->integer('user_id')->default(1);
            $table->integer('staff_id')->default(0);
            $table->boolean('is_private')->default(0)->index();
            $table->boolean('is_torrent')->default(0)->index();
            $table->json('json_torrent');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rss');
        Schema::create('rss', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('userID')->index('userID');
            $table->string('category')->nullable();
            $table->timestamps();
            $table->foreign('userID', 'rss_user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }
}
