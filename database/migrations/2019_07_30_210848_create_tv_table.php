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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tv', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('tmdb_id')->nullable();
            $table->string('imdb_id')->nullable();
            $table->string('tvdb_id')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->index();
            $table->string('name_sort');
            $table->mediumText('overview')->nullable();
            $table->integer('number_of_episodes')->nullable();
            $table->integer('count_existing_episodes')->nullable();
            $table->integer('count_total_episodes')->nullable();
            $table->integer('number_of_seasons')->nullable();
            $table->string('episode_run_time')->nullable();
            $table->string('first_air_date')->nullable();
            $table->string('status')->nullable();
            $table->string('homepage')->nullable();
            $table->boolean('in_production')->nullable();
            $table->string('last_air_date')->nullable();
            $table->string('next_episode_to_air')->nullable();
            $table->string('origin_country')->nullable();
            $table->string('original_language')->nullable();
            $table->string('original_name')->nullable();
            $table->string('popularity')->nullable();
            $table->string('backdrop')->nullable();
            $table->string('poster')->nullable();
            $table->string('vote_average')->nullable();
            $table->integer('vote_count')->nullable();
            $table->timestamps();
        });
    }
};
