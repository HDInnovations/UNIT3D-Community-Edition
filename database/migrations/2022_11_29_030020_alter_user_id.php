<?php

use App\Models\Application;
use App\Models\Article;
use App\Models\Audit;
use App\Models\Ban;
use App\Models\BonTransactions;
use App\Models\Bookmark;
use App\Models\BotTransaction;
use App\Models\Comment;
use App\Models\FailedLoginAttempt;
use App\Models\FeaturedTorrent;
use App\Models\Forum;
use App\Models\FreeleechToken;
use App\Models\Graveyard;
use App\Models\History;
use App\Models\Invite;
use App\Models\Like;
use App\Models\Message;
use App\Models\Note;
use App\Models\Notification;
use App\Models\Peer;
use App\Models\PersonalFreeleech;
use App\Models\Playlist;
use App\Models\Poll;
use App\Models\Post;
use App\Models\PrivateMessage;
use App\Models\Report;
use App\Models\Rss;
use App\Models\Seedbox;
use App\Models\Subscription;
use App\Models\Subtitle;
use App\Models\Thank;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\Topic;
use App\Models\Torrent;
use App\Models\TorrentDownload;
use App\Models\TorrentRequest;
use App\Models\TorrentRequestBounty;
use App\Models\TwoStepAuth;
use App\Models\User;
use App\Models\UserActivation;
use App\Models\UserAudible;
use App\Models\UserEcho;
use App\Models\UserNotification;
use App\Models\UserPrivacy;
use App\Models\Voter;
use App\Models\Warning;
use App\Models\Watchlist;
use App\Models\Wish;
use Assada\Achievements\Model\AchievementProgress;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update user_id columns that are using 0 instead of null
        Schema::table('bon_transactions', function (Blueprint $table) {
            $table->unsignedInteger('sender')->nullable()->default(null)->change();
            $table->unsignedInteger('receiver')->nullable()->default(null)->change();
        });

        BonTransactions::withoutGlobalScopes()->where('sender', '=', 0)->update(['sender' => null]);
        BonTransactions::withoutGlobalScopes()->where('receiver', '=', 0)->update(['receiver' => null]);

        $userIds = User::withoutGlobalScopes()->pluck('id');

        // 1 is ID of the System account
        User::withoutGlobalScopes()
            ->whereIntegerNotInRaw('deleted_by', $userIds)
            ->whereNotNull('deleted_by')
            ->update(['deleted_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        AchievementProgress::withoutGlobalScopes()
            ->whereIntegerNotInRaw('achiever_id', $userIds)
            ->whereNotNull('achiever_id')
            ->update(['achiever_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Application::withoutGlobalScopes()
            ->whereIntegerNotInRaw('moderated_by', $userIds)
            ->whereNotNull('moderated_by')
            ->update(['moderated_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        Application::withoutGlobalScopes()
            ->whereIntegerNotInRaw('accepted_by', $userIds)
            ->whereNotNull('accepted_by')
            ->update(['accepted_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        Article::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Audit::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Ban::withoutGlobalScopes()
            ->whereIntegerNotInRaw('owned_by', $userIds)
            ->whereNotNull('owned_by')
            ->update(['owned_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        Ban::withoutGlobalScopes()
            ->whereIntegerNotInRaw('created_by', $userIds)
            ->whereNotNull('created_by')
            ->update(['created_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        BonTransactions::withoutGlobalScopes()
            ->whereIntegerNotInRaw('sender', $userIds)
            ->whereNotNull('sender')
            ->update(['sender' => 1]);
        BonTransactions::withoutGlobalScopes()
            ->whereIntegerNotInRaw('receiver', $userIds)
            ->whereNotNull('receiver')
            ->update(['receiver' => 1]);
        Bookmark::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        BotTransaction::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Seedbox::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Comment::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        FailedLoginAttempt::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        FeaturedTorrent::withoutGlobalScopes()
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
        Forum::withoutGlobalScopes()
            ->whereIntegerNotInRaw('last_post_user_id', $userIds)
            ->whereNotNull('last_post_user_id')
            ->update(['last_post_user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        FreeleechToken::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Graveyard::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        History::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Invite::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Invite::withoutGlobalScopes()
            ->whereIntegerNotInRaw('accepted_by', $userIds)
            ->whereNotNull('accepted_by')
            ->update(['accepted_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        Like::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Message::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Message::withoutGlobalScopes()
            ->whereIntegerNotInRaw('receiver_id', $userIds)
            ->whereNotNull('receiver_id')
            ->update(['receiver_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Notification::withoutGlobalScopes()
            ->whereIntegerNotInRaw('notifiable_id', $userIds)
            ->whereNotNull('notifiable_id')
            ->update(['notifiable_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Peer::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        PersonalFreeleech::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Playlist::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Poll::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Post::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        PrivateMessage::withoutGlobalScopes()
            ->whereIntegerNotInRaw('sender_id', $userIds)
            ->whereNotNull('sender_id')
            ->update(['sender_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        PrivateMessage::withoutGlobalScopes()
            ->whereIntegerNotInRaw('receiver_id', $userIds)
            ->whereNotNull('receiver_id')
            ->update(['receiver_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Report::withoutGlobalScopes()
            ->whereIntegerNotInRaw('reporter_id', $userIds)
            ->whereNotNull('reporter_id')
            ->update(['reporter_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Report::withoutGlobalScopes()
            ->whereIntegerNotInRaw('staff_id', $userIds)
            ->whereNotNull('staff_id')
            ->update(['staff_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Report::withoutGlobalScopes()
            ->whereIntegerNotInRaw('reported_user', $userIds)
            ->whereNotNull('reported_user')
            ->update(['reported_user' => 1, 'updated_at' => DB::raw('updated_at')]);
        TorrentRequestBounty::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        TorrentRequest::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        TorrentRequest::withoutGlobalScopes()
            ->whereIntegerNotInRaw('filled_by', $userIds)
            ->whereNotNull('filled_by')
            ->update(['filled_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        TorrentRequest::withoutGlobalScopes()
            ->whereIntegerNotInRaw('approved_by', $userIds)
            ->whereNotNull('approved_by')
            ->update(['approved_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        Rss::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Rss::withoutGlobalScopes()
            ->whereIntegerNotInRaw('staff_id', $userIds)
            ->whereNotNull('staff_id')
            ->update(['staff_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Subscription::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Subtitle::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Subtitle::withoutGlobalScopes()
            ->whereIntegerNotInRaw('moderated_by', $userIds)
            ->whereNotNull('moderated_by')
            ->update(['moderated_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        Thank::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        TicketAttachment::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Ticket::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Ticket::withoutGlobalScopes()
            ->whereIntegerNotInRaw('staff_id', $userIds)
            ->whereNotNull('staff_id')
            ->update(['staff_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Topic::withoutGlobalScopes()
            ->whereIntegerNotInRaw('first_post_user_id', $userIds)
            ->whereNotNull('first_post_user_id')
            ->update(['first_post_user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Topic::withoutGlobalScopes()
            ->whereIntegerNotInRaw('last_post_user_id', $userIds)
            ->whereNotNull('last_post_user_id')
            ->update(['last_post_user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        TorrentDownload::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Torrent::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        TwoStepAuth::withoutGlobalScopes()
            ->whereIntegerNotInRaw('userId', $userIds)
            ->whereNotNull('userId')
            ->update(['userId' => 1, 'updated_at' => DB::raw('updated_at')]);
        UserActivation::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        UserAudible::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        UserAudible::withoutGlobalScopes()
            ->whereIntegerNotInRaw('target_id', $userIds)
            ->whereNotNull('target_id')
            ->update(['target_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        UserEcho::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        UserEcho::withoutGlobalScopes()
            ->whereIntegerNotInRaw('target_id', $userIds)
            ->whereNotNull('target_id')
            ->update(['target_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Note::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Note::withoutGlobalScopes()
            ->whereIntegerNotInRaw('staff_id', $userIds)
            ->whereNotNull('staff_id')
            ->update(['staff_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        UserNotification::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1]);
        UserPrivacy::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1]);
        Voter::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Warning::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Warning::withoutGlobalScopes()
            ->whereIntegerNotInRaw('warned_by', $userIds)
            ->whereNotNull('warned_by')
            ->update(['warned_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        Warning::withoutGlobalScopes()
            ->whereIntegerNotInRaw('deleted_by', $userIds)
            ->whereNotNull('deleted_by')
            ->update(['deleted_by' => 1, 'updated_at' => DB::raw('updated_at')]);
        Watchlist::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Watchlist::withoutGlobalScopes()
            ->whereIntegerNotInRaw('staff_id', $userIds)
            ->whereNotNull('staff_id')
            ->update(['staff_id' => 1, 'updated_at' => DB::raw('updated_at')]);
        Wish::withoutGlobalScopes()
            ->whereIntegerNotInRaw('user_id', $userIds)
            ->whereNotNull('user_id')
            ->update(['user_id' => 1, 'updated_at' => DB::raw('updated_at')]);

        // Remove constraint
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign('fk_articles_users1');
        });

        Schema::table('bans', function (Blueprint $table) {
            $table->dropForeign('foreign_ban_user_id');
            $table->dropForeign('foreign_staff_ban_user_id');
        });

        Schema::table('bot_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('fk_comments_users_1');
        });

        Schema::table('history', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('peers', function (Blueprint $table) {
            $table->dropForeign('fk_peers_users1');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign('foreign_reporting_user_id');
            $table->dropForeign('foreign_staff_user_id');
        });

        Schema::table('user_audibles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_echoes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_privacy', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('warnings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign('warnings_warned_by_foreign');
        });

        // Alter column type, remove indexes, and add foreign key constraint
        Schema::table('users', function (Blueprint $table) {
            $table->increments('id')->change();

            $table->unsignedInteger('deleted_by')->nullable()->change();
            $table->foreign('deleted_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('achievement_progress', function (Blueprint $table) {
            $table->unsignedInteger('achiever_id')->change();
            $table->foreign('achiever_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedInteger('moderated_by')->nullable()->change();
            $table->dropIndex(['moderated_by']);
            $table->foreign('moderated_by')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('accepted_by')->nullable()->change();
            $table->dropIndex(['accepted_by']);
            $table->foreign('accepted_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('fk_articles_users1_idx');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('audits', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('bans', function (Blueprint $table) {
            $table->unsignedInteger('owned_by')->change();
            $table->dropIndex('owned_by');
            $table->foreign('owned_by')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('created_by')->nullable()->change();
            $table->dropIndex('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('bon_transactions', function (Blueprint $table) {
            $table->dropIndex(['sender']);
            $table->foreign('sender')->references('id')->on('users')->cascadeOnUpdate();

            $table->dropIndex(['receiver']);
            $table->foreign('receiver')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('bot_transactions', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->default('0')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->dropIndex('fk_comments_users_1');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('failed_login_attempts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('featured_torrents', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('follows', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('target_id')->change();
            $table->dropIndex(['target_id']);
            $table->foreign('target_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('forums', function (Blueprint $table) {
            $table->unsignedInteger('last_post_user_id')->nullable()->change();
            $table->foreign('last_post_user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('freeleech_tokens', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('graveyard', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('history', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('invites', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('accepted_by')->nullable()->change();
            $table->dropIndex('accepted_by');
            $table->foreign('accepted_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
            $table->foreign('receiver_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedInteger('notifiable_id')->change();
            $table->foreign('notifiable_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('peers', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('fk_peers_users1_idx');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('personal_freeleech', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('playlists', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('polls', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->default('0')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('fk_forum_posts_users1_idx');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('private_messages', function (Blueprint $table) {
            $table->dropIndex(['sender_id']);
            $table->foreign('sender_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->dropIndex('private_messages_reciever_id_index');
            $table->foreign('receiver_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedInteger('reporter_id')->change();
            $table->dropIndex('reporter_id');
            $table->foreign('reporter_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('staff_id')->nullable()->change();
            $table->dropIndex('staff_id');
            $table->foreign('staff_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->foreign('reported_user')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('request_bounty', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('addedby');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('requests_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('filled_by')->change();
            $table->dropIndex('filled_by');
            $table->foreign('filled_by')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('approved_by')->change();
            $table->dropIndex('approved_by');
            $table->foreign('approved_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('rss', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->default('1')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('staff_id')->default('0')->change();
            $table->foreign('staff_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('subtitles', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('moderated_by')->change();
            $table->dropIndex(['moderated_by']);
            $table->foreign('moderated_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('thanks', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('ticket_attachments', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('staff_id')->nullable()->change();
            $table->dropIndex(['staff_id']);
            $table->foreign('staff_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->unsignedInteger('first_post_user_id')->nullable()->change();
            $table->foreign('first_post_user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('last_post_user_id')->nullable()->change();
            $table->foreign('last_post_user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('torrent_downloads', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('torrents', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex('fk_torrents_users1_idx');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('twostep_auth', function (Blueprint $table) {
            $table->dropIndex(['userId']);
            $table->foreign('userId')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_activations', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_audibles', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('target_id')->nullable()->change();
            $table->dropIndex(['target_id']);
            $table->foreign('target_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_echoes', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('target_id')->nullable()->change();
            $table->dropIndex(['target_id']);
            $table->foreign('target_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_notes', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->dropIndex(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('staff_id')->change();
            $table->dropIndex(['staff_id']);
            $table->foreign('staff_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_notifications', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->unique()->change();
            $table->dropUnique(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('user_privacy', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->unique()->change();
            $table->dropUnique(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('voters', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('warnings', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('warned_by')->change();
            $table->foreign('warned_by')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('deleted_by')->nullable()->change();
            $table->foreign('deleted_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('watchlists', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->unique()->change();
            $table->dropUnique(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();

            $table->unsignedInteger('staff_id')->change();
            $table->dropIndex(['staff_id']);
            $table->foreign('staff_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('wishes', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });
    }
};
