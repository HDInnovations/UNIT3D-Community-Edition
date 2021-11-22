<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreTorrentPromos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('torrents', function (Blueprint $table) {
            $table->boolean('promo25')->default(0)->after('free');
            $table->boolean('promo50')->default(0)->after('free');
        });
    }

    public function down()
    {
        Schema::table('torrents', function (Blueprint $table) {
            $table->dropColumn('promo25');
            $table->dropColumn('promo50');
        });
    }
}
