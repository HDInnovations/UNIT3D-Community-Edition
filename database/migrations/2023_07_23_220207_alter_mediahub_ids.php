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
        DB::table('company_movie')->whereNotIn('company_id', DB::table('companies')->select('id'))->delete();
        DB::table('company_tv')->whereNotIn('company_id', DB::table('companies')->select('id'))->delete();
        DB::table('collection_movie')->whereNotIn('collection_id', DB::table('collection')->select('id'))->delete();
        DB::table('network_tv')->whereNotIn('network_id', DB::table('networks')->select('id'))->delete();
        DB::table('genre_movie')->whereNotIn('genre_id', DB::table('genres')->select('id'))->delete();
        DB::table('genre_tv')->whereNotIn('genre_id', DB::table('genres')->select('id'))->delete();

        Schema::table('credits', function (Blueprint $table): void {
            $table->dropForeign(['person_id']);
        });

        Schema::table('companies', function (Blueprint $table): void {
            $table->increments('id')->change();
        });

        Schema::table('collection', function (Blueprint $table): void {
            $table->increments('id')->change();
        });

        Schema::table('networks', function (Blueprint $table): void {
            $table->increments('id')->change();
        });

        Schema::table('genres', function (Blueprint $table): void {
            $table->increments('id')->change();
        });

        Schema::table('recommendations', function (Blueprint $table): void {
            $table->increments('id')->change();
        });

        Schema::table('person', function (Blueprint $table): void {
            $table->increments('id')->change();
        });

        Schema::table('company_movie', function (Blueprint $table): void {
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('company_tv', function (Blueprint $table): void {
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('collection_movie', function (Blueprint $table): void {
            $table->foreign('collection_id')->references('id')->on('collection')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('network_tv', function (Blueprint $table): void {
            $table->foreign('network_id')->references('id')->on('networks')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('genre_movie', function (Blueprint $table): void {
            $table->foreign('genre_id')->references('id')->on('genres')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('genre_tv', function (Blueprint $table): void {
            $table->foreign('genre_id')->references('id')->on('genres')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('credits', function (Blueprint $table): void {
            $table->unsignedInteger('person_id')->change();
            $table->foreign('person_id')->references('id')->on('person')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
};
