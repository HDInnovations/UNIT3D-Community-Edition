<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('email');
            $table->longText('referrer')->nullable();
            $table->boolean('status')->default(0);
            $table->dateTime('moderated_at')->nullable();
            $table->integer('moderated_by')->nullable();
            $table->integer('accepted_by')->nullable();
            $table->nullableTimestamps();
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->unique('email', 'applications_email_unique');
            $table->index('accepted_by');
            $table->index('moderated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }

}