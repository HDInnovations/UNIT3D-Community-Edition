<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('forum_id');
            $table->integer('group_id');
            $table->boolean('show_forum');
            $table->boolean('read_topic');
            $table->boolean('reply_topic');
            $table->boolean('start_topic');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->index('group_id', 'fk_permissions_groups1_idx');
            $table->index('forum_id', 'fk_permissions_forums1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }

}