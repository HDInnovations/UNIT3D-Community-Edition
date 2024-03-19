<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        DB::table('recommendations')
            ->whereNotIn('movie_id', DB::table('movie')->select('id'))
            ->orWhereNotIn('recommendation_movie_id', DB::table('movie')->select('id'))
            ->delete();

        DB::table('recommendations')
            ->whereNotIn('tv_id', DB::table('tv')->select('id'))
            ->orWhereNotIn('recommendation_tv_id', DB::table('tv')->select('id'))
            ->delete();

        DB::table('seasons')->whereNotIn('tv_id', DB::table('tv')->select('id'))->delete();
        DB::table('episodes')->whereNotIn('tv_id', DB::table('tv')->select('id'))->delete();
        DB::table('company_movie')->whereNotIn('movie_id', DB::table('movie')->select('id'))->delete();
        DB::table('company_tv')->whereNotIn('tv_id', DB::table('tv')->select('id'))->delete();
        DB::table('collection_movie')->whereNotIn('movie_id', DB::table('movie')->select('id'))->delete();
        DB::table('network_tv')->whereNotIn('tv_id', DB::table('tv')->select('id'))->delete();
        DB::table('genre_movie')->whereNotIn('movie_id', DB::table('movie')->select('id'))->delete();
        DB::table('genre_tv')->whereNotIn('tv_id', DB::table('tv')->select('id'))->delete();

        Schema::table('recommendations', function (Blueprint $table): void {
            $table->dropForeign(['movie_id']);
            $table->dropForeign(['recommendation_movie_id']);
            $table->dropForeign(['tv_id']);
            $table->dropForeign(['recommendation_tv_id']);
        });

        Schema::table('credits', function (Blueprint $table): void {
            $table->dropForeign(['movie_id']);
            $table->dropForeign(['tv_id']);
        });

        Schema::table('movie', function (Blueprint $table): void {
            $table->increments('id')->change();
        });

        Schema::table('tv', function (Blueprint $table): void {
            $table->increments('id')->change();
        });

        Schema::table('recommendations', function (Blueprint $table): void {
            $table->unsignedInteger('movie_id')->change();
            $table->foreign('movie_id')->references('id')->on('movie')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedInteger('recommendation_movie_id')->change();
            $table->foreign('recommendation_movie_id')->references('id')->on('movie')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedInteger('tv_id')->change();
            $table->foreign('tv_id')->references('id')->on('tv')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedInteger('recommendation_tv_id')->change();
            $table->foreign('recommendation_tv_id')->references('id')->on('tv')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('credits', function (Blueprint $table): void {
            $table->unsignedInteger('movie_id')->nullable()->change();
            $table->foreign('movie_id')->references('id')->on('movie')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedInteger('tv_id')->nullable()->change();
            $table->foreign('tv_id')->references('id')->on('tv')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('seasons', function (Blueprint $table): void {
            $table->increments('id')->change();
            $table->unsignedInteger('tv_id')->change();
            $table->foreign('tv_id')->references('id')->on('tv')->cascadeOnUpdate();
        });

        Schema::table('episodes', function (Blueprint $table): void {
            $table->increments('id')->change();
            $table->unsignedInteger('tv_id')->change();
            $table->foreign('tv_id')->references('id')->on('tv')->cascadeOnUpdate();

            $table->unsignedInteger('season_id')->change();
            $table->foreign('season_id')->references('id')->on('seasons')->cascadeOnUpdate();
        });

        Schema::table('company_movie', function (Blueprint $table): void {
            $table->foreign('movie_id')->references('id')->on('movie')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('company_tv', function (Blueprint $table): void {
            $table->foreign('tv_id')->references('id')->on('tv')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('collection_movie', function (Blueprint $table): void {
            $table->foreign('movie_id')->references('id')->on('movie')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('network_tv', function (Blueprint $table): void {
            $table->foreign('tv_id')->references('id')->on('tv')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('genre_movie', function (Blueprint $table): void {
            $table->foreign('movie_id')->references('id')->on('movie')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('genre_tv', function (Blueprint $table): void {
            $table->foreign('tv_id')->references('id')->on('tv')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
};
