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
        $userIds = DB::table('users')->pluck('id');

        // 1 is ID of the System account
        DB::table('users')
            ->whereIntegerNotInRaw('deleted_by', $userIds)
            ->whereNotNull('deleted_by')
            ->update(['deleted_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('achievement_progress')
            ->whereIntegerNotInRaw('achiever_id', $userIds)
            ->whereNotNull('achiever_id')
            ->update(['achiever_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('applications')
            ->whereIntegerNotInRaw('moderated_by', $userIds)
            ->whereNotNull('moderated_by')
            ->update(['moderated_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('applications')
            ->whereIntegerNotInRaw('accepted_by', $userIds)
            ->whereNotNull('accepted_by')
            ->update(['accepted_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('articles')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('audits')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('bans')
            ->whereIntegerNotInRaw('owned_by', $userIds)
            ->whereNotNull('owned_by')
            ->update(['owned_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('bans')
            ->whereIntegerNotInRaw('created_by', $userIds)
            ->whereNotNull('created_by')
            ->update(['created_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('bon_transactions')
            ->whereIntegerNotInRaw('sender', $userIds)
            ->whereNotNull('sender')
            ->update(['sender' => 1]);
        DB::table('bon_transactions')
            ->whereIntegerNotInRaw('receiver', $userIds)
            ->whereNotNull('receiver')
            ->update(['receiver' => 1]);
        DB::table('bookmarks')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('bot_transactions')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('clients')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('comments')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('failed_login_attempts')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('featured_torrents')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('follows')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('follows')
            ->whereIntegerNotInRaw('target_id', $userIds)
            ->whereNotNull('target_id')
            ->update(['target_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('forums')
            ->whereIntegerNotInRaw('last_post_user_id', $userIds)
            ->whereNotNull('last_post_user_id')
            ->update(['last_post_user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('freeleech_tokens')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('graveyard')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('history')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('invites')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('invites')
            ->whereIntegerNotInRaw('accepted_by', $userIds)
            ->whereNotNull('accepted_by')
            ->update(['accepted_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('likes')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('messages')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('messages')
            ->whereIntegerNotInRaw('receiver_id', $userIds)
            ->whereNotNull('receiver_id')
            ->update(['receiver_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('notifications')
            ->whereIntegerNotInRaw('notifiable_id', $userIds)
            ->whereNotNull('notifiable_id')
            ->update(['notifiable_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('peers')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('personal_freeleech')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('playlists')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('polls')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('posts')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('private_messages')
            ->whereIntegerNotInRaw('sender_id', $userIds)
            ->whereNotNull('sender_id')
            ->update(['sender_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('private_messages')
            ->whereIntegerNotInRaw('receiver_id', $userIds)
            ->whereNotNull('receiver_id')
            ->update(['receiver_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('reports')
            ->whereIntegerNotInRaw('reporter_id', $userIds)
            ->whereNotNull('reporter_id')
            ->update(['reporter_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('reports')
            ->whereIntegerNotInRaw('staff_id', $userIds)
            ->whereNotNull('staff_id')
            ->update(['staff_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('reports')
            ->whereIntegerNotInRaw('reported_user', $userIds)
            ->whereNotNull('reported_user')
            ->update(['reported_user' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('request_bounty')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('requests')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('requests')
            ->whereIntegerNotInRaw('filled_by', $userIds)
            ->whereNotNull('filled_by')
            ->update(['filled_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('requests')
            ->whereIntegerNotInRaw('approved_by', $userIds)
            ->whereNotNull('approved_by')
            ->update(['approved_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('rss')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('rss')
            ->whereIntegerNotInRaw('staff_id', $userIds)
            ->whereNotNull('staff_id')
            ->update(['staff_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('subscriptions')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('subtitles')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('subtitles')
            ->whereIntegerNotInRaw('moderated_by', $userIds)
            ->whereNotNull('moderated_by')
            ->update(['moderated_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('thanks')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('ticket_attachments')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('tickets')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('tickets')
            ->whereIntegerNotInRaw('staff_id', $userIds)
            ->whereNotNull('staff_id')
            ->update(['staff_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('topics')
            ->whereIntegerNotInRaw('first_post_user_id', $userIds)
            ->whereNotNull('first_post_user_id')
            ->update(['first_post_user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('topics')
            ->whereIntegerNotInRaw('last_post_user_id', $userIds)
            ->whereNotNull('last_post_user_id')
            ->update(['last_post_user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('torrent_downloads')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('torrents')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('twostep_auth')
            ->whereIntegerNotInRaw('userId', $userIds)
            ->whereNotNull('userId')
            ->update(['userId' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('user_activations')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('user_audibles')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('user_audibles')
            ->whereIntegerNotInRaw('target_id', $userIds)
            ->whereNotNull('target_id')
            ->update(['target_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('user_echoes')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('user_echoes')
            ->whereIntegerNotInRaw('target_id', $userIds)
            ->whereNotNull('target_id')
            ->update(['target_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('user_notes')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('user_notes')
            ->whereIntegerNotInRaw('staff_id', $userIds)
            ->whereNotNull('staff_id')
            ->update(['staff_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('user_notifications')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1]);
        DB::table('user_privacy')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1]);
        DB::table('voters')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('warnings')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('warnings')
            ->whereIntegerNotInRaw('warned_by', $userIds)
            ->whereNotNull('warned_by')
            ->update(['warned_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('warnings')
            ->whereIntegerNotInRaw('deleted_by', $userIds)
            ->whereNotNull('deleted_by')
            ->update(['deleted_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('watchlists')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('watchlists')
            ->whereIntegerNotInRaw('staff_id', $userIds)
            ->whereNotNull('staff_id')
            ->update(['staff_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        DB::table('wishes')
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);

        // Remove constraint
        Schema::table('articles', function (Blueprint $table): void {
            $table->dropForeign('fk_articles_users1');
        });

        Schema::table('bans', function (Blueprint $table): void {
            $table->dropForeign('foreign_ban_user_id');
            $table->dropForeign('foreign_staff_ban_user_id');
        });

        Schema::table('bot_transactions', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
        });

        Schema::table('clients', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
        });

        Schema::table('comments', function (Blueprint $table): void {
            $table->dropForeign('fk_comments_users_1');
        });

        Schema::table('history', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
        });

        Schema::table('peers', function (Blueprint $table): void {
            $table->dropForeign('fk_peers_users1');
        });

        Schema::table('reports', function (Blueprint $table): void {
            $table->dropForeign('foreign_reporting_user_id');
            $table->dropForeign('foreign_staff_user_id');
        });

        Schema::table('user_audibles', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_echoes', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_notifications', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_privacy', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
        });

        Schema::table('warnings', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
            $table->dropForeign('warnings_warned_by_foreign');
        });

        // Alter column type, remove indexes, and add foreign key constraint
        Schema::table('users', function (Blueprint $table): void {
            $table->increments('id')->change();

            $table->unsignedInteger('deleted_by')->nullable()->change();
            $table->foreign('deleted_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('achievement_progress', function (Blueprint $table): void {
            $table->unsignedInteger('achiever_id')->change();
            $table->foreign('achiever_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('applications', function (Blueprint $table): void {
            $table->unsignedInteger('moderated_by')->nullable()->change();
            $table->dropIndex(['moderated_by']);
            $table->foreign('moderated_by')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('accepted_by')->nullable()->change();
            $table->dropIndex(['accepted_by']);
            $table->foreign('accepted_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('articles', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('fk_articles_users1_idx');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('audits', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('bans', function (Blueprint $table): void {
            $table->unsignedInteger('owned_by')->change();
            $table->dropIndex('owned_by');
            $table->foreign('owned_by')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('created_by')->nullable()->change();
            $table->dropIndex('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('bon_transactions', function (Blueprint $table): void {
            $table->dropIndex(['sender']);
            $table->foreign('sender')->references('id')->on('users')->cascadeOnUpdate();

            $table->dropIndex(['receiver']);
            $table->foreign('receiver')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('bookmarks', function (Blueprint $table): void {
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('bot_transactions', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->default('0')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('clients', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('comments', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->dropIndex('fk_comments_users_1');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('failed_login_attempts', function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('featured_torrents', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('follows', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('target_id')->change();
            $table->dropIndex(['target_id']);
            $table->foreign('target_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('forums', function (Blueprint $table): void {
            $table->unsignedInteger('last_post_user_id')->nullable()->change();
            $table->foreign('last_post_user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('freeleech_tokens', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('graveyard', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('history', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('invites', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('accepted_by')->nullable()->change();
            $table->dropIndex('accepted_by');
            $table->foreign('accepted_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('likes', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('messages', function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
            $table->foreign('receiver_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('notifications', function (Blueprint $table): void {
            $table->unsignedInteger('notifiable_id')->change();
            $table->foreign('notifiable_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('peers', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->dropIndex('fk_peers_users1_idx');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('personal_freeleech', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('playlists', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('polls', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->default('0')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('fk_forum_posts_users1_idx');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('private_messages', function (Blueprint $table): void {
            $table->dropIndex(['sender_id']);
            $table->foreign('sender_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->dropIndex('private_messages_reciever_id_index');
            $table->foreign('receiver_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('reports', function (Blueprint $table): void {
            $table->unsignedInteger('reporter_id')->change();
            $table->dropIndex('reporter_id');
            $table->foreign('reporter_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('staff_id')->nullable()->change();
            $table->dropIndex('staff_id');
            $table->foreign('staff_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->foreign('reported_user')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('request_bounty', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('addedby');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('requests', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('requests_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('filled_by')->nullable()->change();
            $table->dropIndex('filled_by');
            $table->foreign('filled_by')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('approved_by')->nullable()->change();
            $table->dropIndex('approved_by');
            $table->foreign('approved_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('rss', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->default('1')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('staff_id')->default('0')->change();
            $table->foreign('staff_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('sessions', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('subscriptions', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('subtitles', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('moderated_by')->change();
            $table->dropIndex(['moderated_by']);
            $table->foreign('moderated_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('thanks', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('ticket_attachments', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('tickets', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('staff_id')->nullable()->change();
            $table->dropIndex(['staff_id']);
            $table->foreign('staff_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('topics', function (Blueprint $table): void {
            $table->unsignedInteger('first_post_user_id')->nullable()->change();
            $table->foreign('first_post_user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('last_post_user_id')->nullable()->change();
            $table->foreign('last_post_user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('torrent_downloads', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('torrents', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('fk_torrents_users1_idx');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('twostep_auth', function (Blueprint $table): void {
            $table->dropIndex(['userId']);
            $table->foreign('userId')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_activations', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_audibles', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('target_id')->nullable()->change();
            $table->dropIndex(['target_id']);
            $table->foreign('target_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_echoes', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('target_id')->nullable()->change();
            $table->dropIndex(['target_id']);
            $table->foreign('target_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_notes', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('staff_id')->change();
            $table->dropIndex(['staff_id']);
            $table->foreign('staff_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_notifications', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->unique()->change();
            $table->dropUnique(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_privacy', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->unique()->change();
            $table->dropUnique(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('voters', function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('warnings', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('warned_by')->change();
            $table->foreign('warned_by')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('deleted_by')->nullable()->change();
            $table->foreign('deleted_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('watchlists', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->unique()->change();
            $table->dropUnique(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('staff_id')->change();
            $table->dropIndex(['staff_id']);
            $table->foreign('staff_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('wishes', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });
    }
};
