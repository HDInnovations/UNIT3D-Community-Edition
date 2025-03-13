<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('igdb_games', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->text('summary')->nullable();
            $table->string('first_artwork_image_id')->nullable();
            $table->timestamp('first_release_date')->nullable();
            $table->string('cover_image_id')->nullable();
            $table->string('url')->nullable();
            $table->float('rating')->nullable();
            $table->unsignedInteger('rating_count')->nullable();
            $table->string('first_video_video_id')->nullable();
            $table->timestamps();
        });

        Schema::create('igdb_platforms', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->string('platform_logo_image_id')->nullable();
        });

        Schema::create('igdb_companies', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('logo_image_id')->nullable();
        });

        Schema::create('igdb_genres', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('igdb_game_igdb_platform', function (Blueprint $table): void {
            $table->unsignedInteger('igdb_game_id');
            $table->unsignedInteger('igdb_platform_id')->index();
            $table->primary(['igdb_game_id', 'igdb_platform_id']);

            $table->foreign('igdb_game_id')->references('id')->on('igdb_games');
            $table->foreign('igdb_platform_id')->references('id')->on('igdb_platforms');
        });

        Schema::create('igdb_company_igdb_game', function (Blueprint $table): void {
            $table->unsignedInteger('igdb_game_id');
            $table->unsignedInteger('igdb_company_id')->index();
            $table->primary(['igdb_game_id', 'igdb_company_id']);

            $table->foreign('igdb_game_id')->references('id')->on('igdb_games');
            $table->foreign('igdb_company_id')->references('id')->on('igdb_companies');
        });

        Schema::create('igdb_game_igdb_genre', function (Blueprint $table): void {
            $table->unsignedInteger('igdb_game_id');
            $table->unsignedInteger('igdb_genre_id')->index();
            $table->primary(['igdb_game_id', 'igdb_genre_id']);

            $table->foreign('igdb_game_id')->references('id')->on('igdb_games');
            $table->foreign('igdb_genre_id')->references('id')->on('igdb_genres');
        });
    }
};
