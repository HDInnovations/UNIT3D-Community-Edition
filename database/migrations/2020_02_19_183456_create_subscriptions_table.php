<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('forum_id')->nullable();
            $table->integer('topic_id')->nullable();
            $table->nullableTimestamps();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index('topic_id');
            $table->index('user_id');
            $table->index('forum_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }

}