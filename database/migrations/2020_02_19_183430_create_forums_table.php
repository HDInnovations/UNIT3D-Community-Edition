<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('forums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position')->nullable();
            $table->integer('num_topic')->nullable();
            $table->integer('num_post')->nullable();
            $table->integer('last_topic_id')->nullable();
            $table->string('last_topic_name')->nullable();
            $table->string('last_topic_slug')->nullable();
            $table->integer('last_post_user_id')->nullable();
            $table->string('last_post_user_username')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->integer('parent_id')->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('forums');
    }

}