<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('bans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owned_by');
            $table->integer('created_by')->nullable();
            $table->text('ban_reason')->nullable();
            $table->text('unban_reason')->nullable();
            $table->dateTime('removed_at')->nullable();
            $table->nullableTimestamps();
        });

        Schema::table('bans', function (Blueprint $table) {
            $table->index('owned_by', 'owned_by');
            $table->index('created_by', 'created_by');

            $table->foreign('owned_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('bans');
    }

}