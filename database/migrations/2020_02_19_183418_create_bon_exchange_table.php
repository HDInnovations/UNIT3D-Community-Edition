<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonExchangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('bon_exchange', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('value')->default(0);
            $table->unsignedInteger('cost')->default(0);
            $table->boolean('upload')->default(0);
            $table->boolean('download')->default(0);
            $table->boolean('personal_freeleech')->default(0);
            $table->boolean('invite')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('bon_exchange');
    }

}