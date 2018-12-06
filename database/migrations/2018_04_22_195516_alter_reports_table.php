<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // new columns
            $table->integer('reported_user')->unsigned();
            $table->integer('torrent_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('reported_user');
            $table->dropColumn('torrent_id');
        });
    }
}
