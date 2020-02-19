<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('reporter_id');
            $table->integer('staff_id')->nullable();
            $table->string('title');
            $table->text('message');
            $table->integer('solved');
            $table->text('verdict')->nullable();
            $table->nullableTimestamps();
            $table->unsignedInteger('reported_user');
            $table->unsignedInteger('torrent_id')->default(0);
            $table->unsignedInteger('request_id')->default(0);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->index('staff_id', 'staff_id');
            $table->index('reporter_id', 'reporter_id');

            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }

}