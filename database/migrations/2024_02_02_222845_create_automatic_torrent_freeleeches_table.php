<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('automatic_torrent_freeleeches', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('position');
            $table->string('name_regex')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->unsignedBigInteger('resolution_id')->nullable();
            $table->integer('freeleech_percentage');
            $table->timestamps();
        });
    }
};
