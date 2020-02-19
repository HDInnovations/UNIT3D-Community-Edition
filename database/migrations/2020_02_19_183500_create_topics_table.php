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

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->string('state')->nullable();
            $table->boolean('pinned')->default(0);
            $table->boolean('approved')->default(0);
            $table->boolean('denied')->default(0);
            $table->boolean('solved')->default(0);
            $table->boolean('invalid')->default(0);
            $table->boolean('bug')->default(0);
            $table->boolean('suggestion')->default(0);
            $table->boolean('implemented')->default(0);
            $table->integer('num_post')->nullable();
            $table->unsignedBigInteger('first_post_user_id')->nullable();
            $table->unsignedBigInteger('last_post_user_id')->nullable();
            $table->string('first_post_user_username')->nullable();
            $table->string('last_post_user_username')->nullable();
            $table->dateTime('last_reply_at')->nullable();
            $table->integer('views')->nullable();
            $table->nullableTimestamps();
            $table->unsignedBigInteger('forum_id');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->index('forum_id', 'fk_topics_forums1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }

}