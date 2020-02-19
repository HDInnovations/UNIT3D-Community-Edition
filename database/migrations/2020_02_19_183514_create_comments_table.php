<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->smallInteger('anon')->default(0);
            $table->unsignedBigInteger('torrent_id')->nullable();
            $table->integer('article_id')->nullable();
            $table->integer('requests_id')->nullable();
            $table->integer('playlist_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->nullableTimestamps();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index('torrent_id', 'fk_comments_torrents_1');
            $table->index('user_id', 'fk_comments_users_1');
            $table->index('article_id', 'fk_comments_articles_1');
            $table->index('playlist_id');

            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }

}