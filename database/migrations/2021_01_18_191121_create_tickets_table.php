<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->integer('category_id')->index();
            $table->integer('priority_id')->index();
            $table->integer('staff_id')->nullable()->index();
            $table->string('subject');
            $table->longText('body');
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('reminded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
