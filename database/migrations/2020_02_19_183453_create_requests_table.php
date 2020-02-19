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

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->string('type');
            $table->string('imdb')->nullable();
            $table->string('tvdb')->nullable();
            $table->string('tmdb')->nullable();
            $table->string('mal')->nullable();
            $table->string('igdb')->default('0');
            $table->text('description');
            $table->unsignedBigInteger('user_id');
            $table->float('bounty', 22, 2);
            $table->integer('votes')->default(0);
            $table->boolean('claimed')->nullable();
            $table->boolean('anon')->default(0);
            $table->nullableTimestamps();
            $table->unsignedBigInteger('filled_by')->nullable();
            $table->string('filled_hash')->nullable();
            $table->dateTime('filled_when')->nullable();
            $table->boolean('filled_anon')->default(0);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_when')->nullable();
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->index('mal', 'mal');
            $table->index('filled_by', 'filled_by');
            $table->index('approved_by', 'approved_by');
            $table->index('category_id', 'category_id');
            $table->index('tvdb', 'tvdb');
            $table->index('tmdb', 'tmdb');
            $table->index('user_id', 'requests_user_id_foreign');
            $table->index('filled_hash', 'filled_hash');
            $table->index('igdb');
            $table->index('imdb', 'imdb');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
