<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
                'personal_release',
                'internal',
                'seedtime',
                'connectable',
            ]);
            $table->decimal('multiplier', 27, 15);
            $table->enum('operation', ['append', 'multiply']);
            $table->string('name');
            $table->string('description');
        });

        Schema::create('bon_earning_conditions', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('bon_earning_id');
            $table->foreign('bon_earning_id')->references('id')->on('bon_earnings')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('operand1', [
                '1',
                'age',
                'size',
                'seeders',
                'leechers',
                'times_completed',
                'personal_release',
                'internal',
                'type_id',
                'seedtime',
                'connectable',
            ]);
            $table->enum('operator', ['<', '>', '<=', '>=', '=', '!=']);
            $table->decimal('operand2', 27, 15);
        });

        DB::table('bon_earnings')->insert([
            [
                'id'          => 1,
                'position'    => 1,
                'variable'    => '1',
                'multiplier'  => 2,
                'operation'   => 'append',
                'name'        => 'Dying Torrent',
                'description' => 'You are the last remaining seeder! (has been downloaded at least 3 times)',
            ],
            [
                'id'          => 2,
                'position'    => 2,
                'variable'    => '1',
                'multiplier'  => 1.5,
                'operation'   => 'append',
                'name'        => 'Legendary Torrent',
                'description' => 'Older than 12 months',
            ],
            [
                'id'          => 3,
                'position'    => 3,
                'variable'    => '1',
                'multiplier'  => 1,
                'operation'   => 'append',
                'name'        => 'Old Torrent',
                'description' => 'Older than 6 months',
            ],
            [
                'id'          => 4,
                'position'    => 4,
                'variable'    => '1',
                'multiplier'  => 0.75,
                'operation'   => 'append',
                'name'        => 'Huge Torrent',
                'description' => 'Torrent Size ≥ 100 GiB',
            ],
            [
                'id'          => 5,
                'position'    => 5,
                'variable'    => '1',
                'multiplier'  => 0.5,
                'operation'   => 'append',
                'name'        => 'Large Torrent',
                'description' => 'Torrent Size ≥ 25 GiB but < 100 GiB',
            ],
            [
                'id'          => 6,
                'position'    => 6,
                'variable'    => '1',
                'multiplier'  => 0.25,
                'operation'   => 'append',
                'name'        => 'Everyday Torrent',
                'description' => 'Torrent Size ≥ 1 GiB but < 25 GiB',
            ],
            [
                'id'          => 7,
                'position'    => 7,
                'variable'    => '1',
                'multiplier'  => 2,
                'operation'   => 'append',
                'name'        => 'Legendary Seeder',
                'description' => 'Seed Time ≥ 1 year',
            ],
            [
                'id'          => 8,
                'position'    => 8,
                'variable'    => '1',
                'multiplier'  => 1,
                'operation'   => 'append',
                'name'        => 'MVP Seeder',
                'description' => 'Seed Time ≥ 6 months but < 1 year',
            ],
            [
                'id'          => 9,
                'position'    => 9,
                'variable'    => '1',
                'multiplier'  => 0.75,
                'operation'   => 'append',
                'name'        => 'Committed Seeder',
                'description' => 'Seed Time ≥ 3 months but < 6 months',
            ],
            [
                'id'          => 10,
                'position'    => 10,
                'variable'    => '1',
                'multiplier'  => 0.5,
                'operation'   => 'append',
                'name'        => 'Team Player Seeder',
                'description' => 'Seed Time ≥ 2 months but < 3 months',
            ],
            [
                'id'          => 11,
                'position'    => 11,
                'variable'    => '1',
                'multiplier'  => 0.25,
                'operation'   => 'append',
                'name'        => 'Participant Seeder',
                'description' => 'Seed Time ≥ 1 month but < 2 months',
            ],
        ]);

        DB::table('bon_earning_conditions')->insert([
            [
                'bon_earning_id' => 1,
                'operand1'       => 'seeders',
                'operator'       => '=',
                'operand2'       => 1,
            ],
            [
                'bon_earning_id' => 1,
                'operand1'       => 'times_completed',
                'operator'       => '>=',
                'operand2'       => 3,
            ],
            [
                'bon_earning_id' => 2,
                'operand1'       => 'age',
                'operator'       => '>=',
                'operand2'       => 12 * 30 * 24 * 3600,
            ],
            [
                'bon_earning_id' => 3,
                'operand1'       => 'age',
                'operator'       => '<',
                'operand2'       => 12 * 30 * 24 * 3600,
            ],
            [
                'bon_earning_id' => 3,
                'operand1'       => 'age',
                'operator'       => '>=',
                'operand2'       => 6 * 30 * 24 * 3600,
            ],
            [
                'bon_earning_id' => 4,
                'operand1'       => 'size',
                'operator'       => '>=',
                'operand2'       => 100 * 1024 * 1024 * 1024,
            ],
            [
                'bon_earning_id' => 5,
                'operand1'       => 'size',
                'operator'       => '<',
                'operand2'       => 100 * 1024 * 1024 * 1024,
            ],
            [
                'bon_earning_id' => 5,
                'operand1'       => 'size',
                'operator'       => '>=',
                'operand2'       => 25 * 1024 * 1024 * 1024,
            ],
            [
                'bon_earning_id' => 6,
                'operand1'       => 'size',
                'operator'       => '<',
                'operand2'       => 25 * 1024 * 1024 * 1024,
            ],
            [
                'bon_earning_id' => 6,
                'operand1'       => 'size',
                'operator'       => '>=',
                'operand2'       => 1 * 1024 * 1024 * 1024,
            ],
            [
                'bon_earning_id' => 7,
                'operand1'       => 'seedtime',
                'operator'       => '>=',
                'operand2'       => 12 * 30 * 24 * 3600,
            ],
            [
                'bon_earning_id' => 8,
                'operand1'       => 'seedtime',
                'operator'       => '<',
                'operand2'       => 12 * 30 * 24 * 3600,
            ],
            [
                'bon_earning_id' => 8,
                'operand1'       => 'seedtime',
                'operator'       => '>=',
                'operand2'       => 6 * 30 * 24 * 3600,
            ],
            [
                'bon_earning_id' => 9,
                'operand1'       => 'seedtime',
                'operator'       => '<',
                'operand2'       => 6 * 30 * 24 * 3600,
            ],
            [
                'bon_earning_id' => 9,
                'operand1'       => 'seedtime',
                'operator'       => '>=',
                'operand2'       => 3 * 30 * 24 * 3600,
            ],
            [
                'bon_earning_id' => 10,
                'operand1'       => 'seedtime',
                'operator'       => '<',
                'operand2'       => 3 * 30 * 24 * 3600,
            ],
            [
                'bon_earning_id' => 10,
                'operand1'       => 'seedtime',
                'operator'       => '>=',
                'operand2'       => 2 * 30 * 24 * 3600,
            ],
            [
                'bon_earning_id' => 11,
                'operand1'       => 'seedtime',
                'operator'       => '<',
                'operand2'       => 2 * 30 * 24 * 3600,
            ],
            [
                'bon_earning_id' => 11,
                'operand1'       => 'seedtime',
                'operator'       => '>=',
                'operand2'       => 1 * 30 * 24 * 3600,
            ],
        ]);
    }
};
