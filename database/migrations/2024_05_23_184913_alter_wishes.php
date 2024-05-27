<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        DB::table('wishes')->update([
            'tmdb' => DB::raw("REGEXP_REPLACE(tmdb, 'tt', '')"),
        ]);

        DB::table('wishes')
            ->where('tmdb', 'not regexp', '^[0-9]+$')
            ->delete();

        DB::table('wishes')
            ->whereNull('tmdb')
            ->orWhere('tmdb', '<', 0)
            ->orWhere('tmdb', '>', 2_000_000_000)
            ->delete();

        Schema::table('wishes', function (Blueprint $table): void {
            $table->dropColumn(['source', 'type']);
            $table->integer('tmdb')->unsigned()->nullable()->change();
            $table->integer('tv_id')->unsigned()->nullable()->after('tmdb')->index();
            $table->renameColumn('tmdb', 'movie_id');
        });
    }
};
