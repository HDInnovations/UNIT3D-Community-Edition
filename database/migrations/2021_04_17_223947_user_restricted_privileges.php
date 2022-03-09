<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('user_restricted_privilege', function (Blueprint $table) {
            $table->integer('user_id');
            $table->foreignId('privilege_id');

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('privilege_id')->references('id')->on('privileges');

            //SETTING THE PRIMARY KEYS
            $table->primary(['user_id', 'privilege_id']);
        });

        Schema::create('role_restricted_privilege', function (Blueprint $table) {
            $table->foreignId('role_id');
            $table->foreignId('privilege_id');

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('privilege_id')->references('id')->on('privileges');

            //SETTING THE PRIMARY KEYS
            $table->primary(['role_id', 'privilege_id']);
        });

        Schema::enableForeignKeyConstraints();
    }
};
