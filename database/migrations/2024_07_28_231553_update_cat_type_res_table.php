<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // Drop old foreign key
        Schema::table('torrents', function (Blueprint $table): void {
            $table->dropForeign('category_id');
        });

        // Change primary keys to unsigned smallint
        Schema::table('categories', function (Blueprint $table): void {
            $table->smallIncrements('id')->change();
        });

        Schema::table('types', function (Blueprint $table): void {
            $table->smallIncrements('id')->change();
        });

        Schema::table('resolutions', function (Blueprint $table): void {
            $table->smallIncrements('id')->change();
        });

        // Change the related torrent table columns to unsigned smallint
        Schema::table('torrents', function (Blueprint $table): void {
            $table->unsignedSmallInteger('category_id')->nullable()->change();
            $table->unsignedSmallInteger('type_id')->nullable()->change();
            $table->unsignedSmallInteger('resolution_id')->nullable()->change();
        });

        // Change the related requests table columns to unsigned smallint
        Schema::table('requests', function (Blueprint $table): void {
            $table->unsignedSmallInteger('category_id')->change();
            $table->unsignedSmallInteger('type_id')->change();
            $table->unsignedSmallInteger('resolution_id')->nullable()->change();
        });

        // Check for non-existent categories, resolutions, and types in torrents and requests
        $this->handleNonExistentRelations('categories', 'category_id');
        $this->handleNonExistentRelations('resolutions', 'resolution_id');
        $this->handleNonExistentRelations('types', 'type_id');

        // Add constraints with on delete restrict
        Schema::table('torrents', function (Blueprint $table): void {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
            $table->foreign('resolution_id')->references('id')->on('resolutions')->onDelete('restrict');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('restrict');
        });

        Schema::table('requests', function (Blueprint $table): void {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
            $table->foreign('resolution_id')->references('id')->on('resolutions')->onDelete('restrict');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('restrict');
        });
    }

    private function handleNonExistentRelations(string $table, string $column): void
    {
        $nonExistentIds = DB::table('torrents')
            ->leftJoin($table, "torrents.{$column}", '=', "{$table}.id")
            ->whereNull("{$table}.id")
            ->pluck("torrents.{$column}");

        if ($nonExistentIds->isNotEmpty()) {
            $otherId = DB::table($table)->where('name', 'Other')->value('id');

            if (!$otherId) {
                $otherId = DB::table($table)->insertGetId(['name' => 'Other']);
            }

            DB::table('torrents')
                ->whereIntegerInRaw($column, $nonExistentIds)
                ->update([$column => $otherId]);
        }

        $nonExistentIds = DB::table('requests')
            ->leftJoin($table, "requests.{$column}", '=', "{$table}.id")
            ->whereNull("{$table}.id")
            ->pluck("requests.{$column}");

        if ($nonExistentIds->isNotEmpty()) {
            $otherId = DB::table($table)->where('name', 'Other')->value('id');

            if (!$otherId) {
                $otherId = DB::table($table)->insertGetId(['name' => 'Other']);
            }

            DB::table('requests')
                ->whereIntegerInRaw($column, $nonExistentIds)
                ->update([$column => $otherId]);
        }
    }
};
