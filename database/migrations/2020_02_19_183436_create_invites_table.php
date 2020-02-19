<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('invites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('email');
            $table->string('code');
            $table->dateTime('expires_on')->nullable();
            $table->integer('accepted_by')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->text('custom')->nullable();
            $table->nullableTimestamps();
        });

        Schema::table('invites', function (Blueprint $table) {
            $table->index('accepted_by', 'accepted_by');
            $table->index('user_id', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('invites');
    }

}