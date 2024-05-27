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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::drop('cast');
        Schema::drop('cast_episode');
        Schema::drop('cast_movie');
        Schema::drop('cast_season');
        Schema::drop('cast_tv');
        Schema::drop('crew_episode');
        Schema::drop('crew_movie');
        Schema::drop('crew_season');
        Schema::drop('crew_tv');
        Schema::drop('episode_guest_star');
        Schema::drop('episode_person');
        Schema::drop('person_movie');
        Schema::drop('person_season');
        Schema::drop('person_tv');

        Schema::create('occupations', function (Blueprint $table): void {
            $table->smallIncrements('id');
            $table->smallInteger('position');
            $table->string('name');
        });

        DB::table('occupations')->insert([
            [
                'id'       => 1,
                'position' => 1,
                'name'     => 'Creator',
            ],
            [
                'id'       => 2,
                'position' => 2,
                'name'     => 'Director',
            ],
            [
                'id'       => 3,
                'position' => 3,
                'name'     => 'Writer',
            ],
            [
                'id'       => 4,
                'position' => 4,
                'name'     => 'Producer',
            ],
            [
                'id'       => 5,
                'position' => 5,
                'name'     => 'Composer',
            ],
            [
                'id'       => 6,
                'position' => 6,
                'name'     => 'Cinematographer',
            ],
            [
                'id'       => 7,
                'position' => 7,
                'name'     => 'Editor',
            ],
            [
                'id'       => 8,
                'position' => 8,
                'name'     => 'Production Designer',
            ],
            [
                'id'       => 9,
                'position' => 9,
                'name'     => 'Art Director',
            ],
            [
                'id'       => 10,
                'position' => 10,
                'name'     => 'Actor',
            ],
        ]);

        Schema::create('credits', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('movie_id')->nullable();
            $table->unsignedBigInteger('tv_id')->nullable();
            $table->unsignedSmallInteger('occupation_id');
            $table->unsignedInteger('order')->nullable();
            $table->string('character')->nullable();

            $table->unique(['person_id', 'movie_id', 'tv_id', 'occupation_id', 'character']);

            $table->foreign('person_id')->references('id')->on('person')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('movie_id')->references('id')->on('movie')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('tv_id')->references('id')->on('tv')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('occupation_id')->references('id')->on('occupations')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
};
