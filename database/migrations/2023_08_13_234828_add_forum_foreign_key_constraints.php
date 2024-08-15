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
        // Topic id

        Schema::table('topics', function (Blueprint $table): void {
            $table->increments('id')->change();
        });

        // Subscriptions topic_id

        DB::table('subscriptions')
            ->whereNotIn('topic_id', DB::table('topics')->select('id'))
            ->whereNotNull('topic_id')
            ->delete();

        Schema::table('subscriptions', function (Blueprint $table): void {
            $table->unsignedInteger('topic_id')->nullable()->change();
            $table->foreign('topic_id')->references('id')->on('topics')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // Posts topic_id

        DB::table('posts')
            ->whereNotIn('topic_id', DB::table('topics')->select('id'))
            ->whereNotNull('topic_id')
            ->delete();

        Schema::table('posts', function (Blueprint $table): void {
            $table->unsignedInteger('topic_id')->change();
            $table->foreign('topic_id')->references('id')->on('topics')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // Forums last_topic_id

        DB::table('forums')
            ->whereNotIn('last_topic_id', DB::table('topics')->select('id'))
            ->whereNotNull('last_topic_id')
            ->update([
                'last_topic_id' => null,
            ]);

        Schema::table('forums', function (Blueprint $table): void {
            $table->unsignedInteger('last_topic_id')->nullable()->change();
            $table->foreign('last_topic_id')->references('id')->on('topics')->cascadeOnUpdate()->nullOnDelete();
        });

        // Forums id

        Schema::table('forums', function (Blueprint $table): void {
            $table->smallIncrements('id')->change();
        });

        // Subscriptions forum_id

        DB::table('subscriptions')
            ->whereNotIn('forum_id', DB::table('forums')->select('id'))
            ->whereNotNull('forum_id')
            ->delete();

        Schema::table('subscriptions', function (Blueprint $table): void {
            $table->unsignedSmallInteger('forum_id')->nullable()->change();
            $table->foreign('forum_id')->references('id')->on('forums')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // Topics forum_id

        DB::table('topics')
            ->whereNotIn('forum_id', DB::table('forums')->select('id'))
            ->delete();

        Schema::table('topics', function (Blueprint $table): void {
            $table->unsignedSmallInteger('forum_id')->change();
            $table->foreign('forum_id')->references('id')->on('forums')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // Permissions forum_id

        DB::table('permissions')
            ->whereNotIn('forum_id', DB::table('forums')->select('id'))
            ->delete();

        Schema::table('permissions', function (Blueprint $table): void {
            $table->unsignedSmallInteger('forum_id')->change();
            $table->foreign('forum_id')->references('id')->on('forums')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // Forums parent_id

        $forumIds = DB::table('forums')->pluck('id');

        DB::table('forums')
            ->whereIntegerNotInRaw('parent_id', $forumIds)
            ->update([
                'parent_id' => null,
            ]);

        Schema::table('forums', function (Blueprint $table): void {
            $table->unsignedSmallInteger('parent_id')->nullable()->change();
            $table->foreign('parent_id')->references('id')->on('forums')->cascadeOnUpdate()->nullOnDelete();
        });
    }
};
