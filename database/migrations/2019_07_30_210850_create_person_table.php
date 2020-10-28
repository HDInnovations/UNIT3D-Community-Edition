<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->string('imdb_id')->nullable();
            $table->string('known_for_department')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('popularity')->nullable();
            $table->string('profile')->nullable();
            $table->string('still')->nullable();
            $table->string('adult')->nullable();
            $table->mediumText('also_known_as')->nullable();
            $table->mediumText('biography')->nullable();
            $table->string('birthday')->nullable();
            $table->string('deathday')->nullable();
            $table->string('gender')->nullable();
            $table->string('homepage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person');
    }
}
