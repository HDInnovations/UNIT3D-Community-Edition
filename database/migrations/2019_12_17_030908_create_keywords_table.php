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
        Schema::create('keywords', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->integer('torrent_id')->unsigned()->index();
            $table->unique(['torrent_id', 'name']);
            $table->timestamps();
        });
    }
};
