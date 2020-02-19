<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('bot_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable()->default('');
            $table->float('cost', 22, 2)->default(0.00);
            $table->integer('user_id')->default(0);
            $table->integer('bot_id')->default(0);
            $table->boolean('to_user')->default(0);
            $table->boolean('to_bot')->default(0);
            $table->text('comment');
            $table->nullableTimestamps();
        });

        Schema::table('bot_transactions', function (Blueprint $table) {
            $table->index('type');
            $table->index('bot_id');
            $table->index('to_bot');
            $table->index('user_id');
            $table->index('to_user');

            $table->foreign('bot_id')->references('id')->on('bots')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('bot_transactions');
    }

}