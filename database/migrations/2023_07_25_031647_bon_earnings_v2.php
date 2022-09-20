<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('bon_earnings', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('position')->unique();
            $table->enum('variable', [
                '1',
                'age',
                'size',
                'seeders',
                'leechers',
                'times_completed',
                'seedtime',
                'personal_release',
                'internal',
                'connectable',
            ]);
            $table->double('multiplier');
            $table->enum('operation', ['append', 'multiply']);
            $table->string('name');
            $table->string('description');
        });

        Schema::create('bon_earning_conditions', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('bon_earning_id');
            $table->foreign('bon_earning_id')->references('id')->on('bon_earnings');
            $table->enum('operand1', [
                '1',
                'age',
                'size',
                'seeders',
                'leechers',
                'times_completed',
                'seedtime',
                'personal_release',
                'internal',
                'connectable',
            ]);
            $table->enum('operator', ['<', '>', '<=', '>=', '=', '<>']);
            $table->double('operand2');
        });
    }

    public function down(): void
    {
        Schema::drop('bon_earning_conditions');
        Schema::drop('bon_earnings');
    }
};
