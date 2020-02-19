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

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->unsignedBigInteger('reporter_id');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->string('title');
            $table->text('message');
            $table->integer('solved');
            $table->text('verdict')->nullable();
            $table->nullableTimestamps();
            $table->unsignedBigInteger('reported_user');
            $table->unsignedBigInteger('torrent_id')->default(0);
            $table->unsignedBigInteger('request_id')->default(0);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->index('staff_id', 'staff_id');
            $table->index('reporter_id', 'reporter_id');

            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}