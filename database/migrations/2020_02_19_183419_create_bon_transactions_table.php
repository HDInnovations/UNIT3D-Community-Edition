<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('bon_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('itemID')->default(0);
            $table->string('name')->default('');
            $table->float('cost', 22, 2)->default(0.00);
            $table->unsignedInteger('sender')->default(0);
            $table->unsignedInteger('receiver')->default(0);
            $table->integer('torrent_id')->nullable();
            $table->integer('post_id')->nullable();
            $table->text('comment');
            $table->timestamp('date_actioned')->useCurrent();
        });

        Schema::table('bon_transactions', function (Blueprint $table) {
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('bon_transactions');
    }

}