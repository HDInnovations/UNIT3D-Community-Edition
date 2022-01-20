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
        Schema::table('options', function (Blueprint $table) {
            $table->foreign('poll_id', 'fk_options_poll')->references('id')->on('polls')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }
};
