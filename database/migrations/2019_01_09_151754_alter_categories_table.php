<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('meta');
            $table->string('image')->nullable()->after('slug');
            $table->boolean('movie_meta')->default(0);
            $table->boolean('tv_meta')->default(0);
            $table->boolean('game_meta')->default(0);
            $table->boolean('music_meta')->default(0);
            $table->boolean('no_meta')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
