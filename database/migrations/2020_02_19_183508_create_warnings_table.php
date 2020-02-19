<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
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
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('warnings');
    }

}