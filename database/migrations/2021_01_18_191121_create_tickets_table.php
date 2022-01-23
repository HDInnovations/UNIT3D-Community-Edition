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
};
