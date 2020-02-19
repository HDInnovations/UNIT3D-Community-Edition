<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->nullableTimestamps();
            $table->integer('user_id');
            $table->integer('topic_id');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->index('user_id', 'fk_forum_posts_users1_idx');
            $table->index('topic_id', 'fk_posts_topics1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }

}