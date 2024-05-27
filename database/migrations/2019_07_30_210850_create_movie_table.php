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
        Schema::create('movie', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('tmdb_id')->nullable();
            $table->string('imdb_id')->nullable();
            $table->string('title')->index();
            $table->string('title_sort');
            $table->string('original_language')->nullable();
            $table->boolean('adult')->nullable();
            $table->string('backdrop')->nullable();
            $table->string('budget')->nullable();
            $table->string('homepage')->nullable();
            $table->string('original_title')->nullable();
            $table->mediumText('overview')->nullable();
            $table->string('popularity')->nullable();
            $table->string('poster')->nullable();
            $table->date('release_date')->nullable();
            $table->string('revenue')->nullable();
            $table->string('runtime')->nullable();
            $table->string('status')->nullable();
            $table->string('tagline')->nullable();
            $table->string('vote_average')->nullable();
            $table->integer('vote_count')->nullable();
            $table->timestamps();
        });
    }
};
