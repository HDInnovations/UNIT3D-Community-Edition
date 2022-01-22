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
        Schema::create('seasons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tv_id');
            $table->integer('season_number');
            $table->string('name')->nullable()->index();
            $table->mediumText('overview')->nullable();
            $table->string('poster')->nullable();
            $table->string('air_date')->nullable();
            $table->timestamps();
        });
    }
};
