<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationImageProofsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('application_image_proofs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id');
            $table->string('image');
            $table->nullableTimestamps();
        });

        Schema::table('application_image_proofs', function (Blueprint $table) {
            $table->index('application_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('application_image_proofs');
    }

}