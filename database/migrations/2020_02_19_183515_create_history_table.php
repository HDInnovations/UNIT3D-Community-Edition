<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('agent')->nullable();
            $table->string('info_hash');
            $table->unsignedBigInteger('uploaded')->nullable();
            $table->unsignedBigInteger('actual_uploaded')->nullable();
            $table->unsignedBigInteger('client_uploaded')->nullable();
            $table->unsignedBigInteger('downloaded')->nullable();
            $table->unsignedBigInteger('actual_downloaded')->nullable();
            $table->unsignedBigInteger('client_downloaded')->nullable();
            $table->boolean('seeder')->default(0);
            $table->boolean('active')->default(0);
            $table->unsignedBigInteger('seedtime')->default(0);
            $table->boolean('immune')->default(0);
            $table->boolean('hitrun')->default(0);
            $table->boolean('prewarn')->default(0);
            $table->nullableTimestamps();
            $table->dateTime('completed_at')->nullable();
        });

        Schema::table('history', function (Blueprint $table) {
            $table->index('info_hash', 'info_hash');
            $table->index('hitrun');
            $table->index('user_id', 'history_user_id_foreign');
            $table->index('immune');
            $table->index('prewarn');

            $table->foreign('info_hash')->references('info_hash')->on('torrents')->onDelete('cascade');
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
        Schema::dropIfExists('history');
    }

}