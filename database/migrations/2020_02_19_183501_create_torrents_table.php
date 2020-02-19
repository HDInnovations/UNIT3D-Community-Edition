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

class CreateTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torrents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->text('mediainfo')->nullable();
            $table->string('info_hash');
            $table->string('file_name');
            $table->integer('num_file');
            $table->float('size', 10, 0);
            $table->text('nfo')->nullable();
            $table->integer('leechers')->default(0);
            $table->integer('seeders')->default(0);
            $table->integer('times_completed')->default(0);
            $table->integer('category_id')->nullable();
            $table->string('announce');
            $table->integer('user_id');
            $table->string('imdb')->default('0');
            $table->string('tvdb')->default('0');
            $table->string('tmdb')->default('0');
            $table->string('mal')->default('0');
            $table->string('igdb')->default('0');
            $table->string('type');
            $table->boolean('stream')->default(0);
            $table->boolean('free')->default(0);
            $table->boolean('doubleup')->default(0);
            $table->boolean('highspeed')->default(0);
            $table->boolean('featured')->default(0);
            $table->smallInteger('status')->default(0);
            $table->dateTime('moderated_at')->nullable();
            $table->integer('moderated_by')->nullable();
            $table->smallInteger('anon')->default(0);
            $table->smallInteger('sticky')->default(0);
            $table->boolean('sd')->default(0);
            $table->boolean('internal')->default(0);
            $table->nullableTimestamps();
            $table->date('release_year')->nullable();
        });

        Schema::table('torrents', function (Blueprint $table) {
            $table->index('mal', 'mal');
            $table->index('moderated_by', 'moderated_by');
            $table->index('release_year');
            $table->index('info_hash', 'info_hash');
            $table->index('user_id', 'fk_torrents_users1_idx');
            $table->index('tvdb', 'tvdb');
            $table->index('tmdb', 'tmdb');
            $table->index('type', 'type');
            $table->index('igdb');
            $table->index('name', 'name');
            $table->index('category_id', 'fk_table1_categories1_idx');
            $table->index('imdb', 'imdb');

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('torrents');
    }
}