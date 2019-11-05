<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_transactions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('type')->default('')->nullable()->index();
            $table->float('cost', 22)->default(0.00);
            $table->integer('user_id')->default(0)->index();
            $table->integer('bot_id')->default(0)->index();
            $table->boolean('to_user')->default(0)->index();
            $table->boolean('to_bot')->default(0)->index();
            $table->text('comment', 65535);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('bot_id')->references('id')->on('bots')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bot_transactions', function (Blueprint $table) {
            //
        });
    }
}
