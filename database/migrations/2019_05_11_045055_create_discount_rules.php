<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('category')->default(0);
            $table->integer('torrent_min_size')->default(0);
            $table->integer('torrent_max_size')->default(0);
            $table->integer('discount');
            $table->tinyInteger('freeleech')->default(0);
            $table->integer('freeleech_time')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_rules');
    }
}
