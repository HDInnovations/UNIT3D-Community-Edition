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
        Schema::create('topic_reads', function (Blueprint $table): void {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('topic_id')->index();
            $table->integer('last_read_post_id');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('topic_id')->references('id')->on('topics')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('last_read_post_id')->references('id')->on('posts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(['user_id', 'topic_id']);
        });
    }
};
