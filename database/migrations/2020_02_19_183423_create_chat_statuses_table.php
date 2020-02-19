<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('chat_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('color');
            $table->string('icon')->nullable();
            $table->nullableTimestamps();
        });

        Schema::table('chat_statuses', function (Blueprint $table) {
            $table->unique('name', 'chat_statuses_name_unique');
            $table->unique('color', 'chat_statuses_color_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('chat_statuses');
    }

}