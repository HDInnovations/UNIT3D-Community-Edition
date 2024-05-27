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
 * @author     HDVinnie <hdinnovations@protonmail.com>
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
        Schema::create('recommendations', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('poster')->nullable();
            $table->string('vote_average')->nullable();
            $table->date('release_date')->nullable();
            $table->date('first_air_date')->nullable();

            $table->unsignedBigInteger('movie_id')->nullable()->index();
            $table->foreign('movie_id')->references('id')->on('movie')->onDelete('cascade');

            $table->unsignedBigInteger('recommendation_movie_id')->nullable()->index();
            $table->foreign('recommendation_movie_id')->references('id')->on('movie')->onDelete('cascade');

            $table->unsignedBigInteger('tv_id')->nullable()->index();
            $table->foreign('tv_id')->references('id')->on('tv')->onDelete('cascade');

            $table->unsignedBigInteger('recommendation_tv_id')->nullable()->index();
            $table->foreign('recommendation_tv_id')->references('id')->on('tv')->onDelete('cascade');

            $table->unique(['movie_id', 'recommendation_movie_id']);
            $table->unique(['tv_id', 'recommendation_tv_id']);
        });
    }
};
