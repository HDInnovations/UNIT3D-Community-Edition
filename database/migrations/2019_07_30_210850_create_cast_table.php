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
        Schema::create('cast', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->mediumText('character')->nullable();
            $table->string('credit_id')->nullable();
            $table->mediumText('gender')->nullable();
            $table->string('order')->nullable();
            $table->string('still')->nullable();
        });
    }
};
