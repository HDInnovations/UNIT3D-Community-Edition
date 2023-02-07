<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('bots', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('distributors', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('forums', function (Blueprint $table): void {
            $table->dropColumn('last_topic_slug');
        });

        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('polls', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('regions', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('resolutions', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('topics', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('torrents', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('types', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });
    }
};
