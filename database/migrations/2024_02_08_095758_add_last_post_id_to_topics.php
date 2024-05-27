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
    public function up(): void
    {
        Schema::table('topics', function (Blueprint $table): void {
            $table->integer('last_post_id')->nullable()->after('first_post_user_id');

            $table->dropColumn(['first_post_user_username', 'last_post_user_username']);
            $table->foreign('last_post_id')->references('id')->on('posts')->cascadeOnUpdate()->nullOnDelete();

            $table->renameColumn('last_reply_at', 'last_post_created_at');

            $table->index('last_post_created_at');
        });

        DB::table('topics')
            ->update([
                'last_post_id' => DB::raw('(SELECT MAX(id) FROM posts WHERE posts.topic_id = topics.id)'),
            ]);

        Schema::table('forums', function (Blueprint $table): void {
            $table->dropColumn(['last_topic_name', 'last_post_user_username']);

            $table->integer('last_post_id')->nullable()->after('last_topic_id');
            $table->timestamp('last_post_created_at')->nullable()->after('last_post_user_id')->index();

            $table->foreign('last_post_id')->references('id')->on('posts')->cascadeOnUpdate()->nullOnDelete();
        });

        DB::table('forums')
            ->update([
                'last_post_id'         => DB::raw('(SELECT MAX(id) FROM posts WHERE posts.topic_id IN (SELECT id FROM topics WHERE forum_id = forums.id))'),
                'last_post_created_at' => DB::raw('(SELECT MAX(created_at) FROM posts WHERE posts.topic_id IN (SELECT id FROM topics WHERE forum_id = forums.id))'),
            ]);
    }
};
