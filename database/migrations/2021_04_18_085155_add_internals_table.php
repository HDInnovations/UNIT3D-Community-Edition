<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Create Table
        Schema::create('internals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('icon')->default('none');
            $table->string('effect')->default('none');
        });

        //Update Users Table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('internal_id')->index('fk_users_internal_idx')->after('group_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Delete Table
        Schema::dropIfExists('internals');

        //Update Users Table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('internal_id');
        });
    }
};
