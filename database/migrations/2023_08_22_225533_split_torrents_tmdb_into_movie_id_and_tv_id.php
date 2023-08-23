<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('torrents', function (Blueprint $table): void {
            $table->unsignedInteger('movie_id')->nullable()->after('user_id');
            $table->unsignedInteger('tv_id')->nullable()->after('movie_id');

            $table->index(['movie_id', 'status']);
            $table->index(['tv_id', 'status']);
            $table->index(['movie_id', 'tv_id', 'status']);

            $table->foreign('movie_id')->references('id')->on('movie');
            $table->foreign('tv_id')->references('id')->on('tv');
        });

        DB::table('torrents')
            ->whereIn('category_id', DB::table('categories')->select('id')->where('movie_meta', '=', true))
            ->whereIn('tmdb', DB::table('movie')->select('id'))
            ->update([
                'movie_id' => DB::raw('tmdb'),
            ]);

        DB::table('torrents')
            ->whereIn('category_id', DB::table('categories')->select('id')->where('tv_meta', '=', true))
            ->whereIn('tmdb', DB::table('tv')->select('id'))
            ->update([
                'tv_id' => DB::raw('tmdb'),
            ]);

        Schema::table('torrents', function (Blueprint $table): void {
            $table->dropColumn('tmdb');
        });
    }
};
