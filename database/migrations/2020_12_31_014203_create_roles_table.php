<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->integer('position');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color')->default('rgb(0,0,0)');
            $table->string('icon')->nullable();
            $table->string('effect')->default('none')->nullable();
            $table->integer('rule_id')->nullable();
            $table->integer('download_slots')->nullable()->index();
            $table->boolean('system_required')->default(false);
            $table->timestamps();
        });
    }
};
