<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('position');
            $table->integer('level')->default(0);
            $table->string('color');
            $table->string('icon');
            $table->string('effect')->default('none');
            $table->boolean('is_internal')->default(0);
            $table->boolean('is_owner')->default(0);
            $table->boolean('is_admin')->default(0);
            $table->boolean('is_modo')->default(0);
            $table->boolean('is_trusted')->default(0);
            $table->boolean('is_immune')->default(0);
            $table->boolean('is_freeleech')->default(0);
            $table->boolean('can_upload')->default(1);
            $table->boolean('is_incognito')->default(0);
            $table->boolean('autogroup')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }

}