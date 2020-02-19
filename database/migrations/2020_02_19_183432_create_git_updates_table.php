<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGitUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('git_updates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('hash');
            $table->nullableTimestamps();
        });

        Schema::table('git_updates', function (Blueprint $table) {
            $table->unique('name', 'git_updates_name_unique');
            $table->unique('hash', 'git_updates_hash_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('git_updates');
    }

}