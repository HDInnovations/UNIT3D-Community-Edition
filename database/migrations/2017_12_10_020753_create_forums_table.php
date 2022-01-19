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

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forums', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('position')->nullable();
            $table->integer('num_topic')->nullable();
            $table->integer('num_post')->nullable();
            $table->integer('last_topic_id')->nullable();
            $table->string('last_topic_name')->nullable();
            $table->string('last_topic_slug')->nullable();
            $table->integer('last_post_user_id')->nullable();
            $table->string('last_post_user_username')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description', 65535)->nullable();
            $table->integer('parent_id')->nullable();
            $table->timestamps();
        });
    }
};
