<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPrivilegeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('user_privilege', function (Blueprint $table) {
            $table->integer('user_id');
            $table->foreignId('privilege_id');

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('privilege_id')->references('id')->on('privileges');

            //SETTING THE PRIMARY KEYS
            $table->primary(['user_id', 'privilege_id']);
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_privilege');
    }
}
