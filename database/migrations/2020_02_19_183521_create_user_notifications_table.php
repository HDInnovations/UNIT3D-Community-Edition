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

class CreateUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('show_bon_gift')->default(1);
            $table->boolean('show_mention_forum_post')->default(1);
            $table->boolean('show_mention_article_comment')->default(1);
            $table->boolean('show_mention_request_comment')->default(1);
            $table->boolean('show_mention_torrent_comment')->default(1);
            $table->boolean('show_subscription_topic')->default(1);
            $table->boolean('show_subscription_forum')->default(1);
            $table->boolean('show_forum_topic')->default(1);
            $table->boolean('show_following_upload')->default(1);
            $table->boolean('show_request_bounty')->default(1);
            $table->boolean('show_request_comment')->default(1);
            $table->boolean('show_request_fill')->default(1);
            $table->boolean('show_request_fill_approve')->default(1);
            $table->boolean('show_request_fill_reject')->default(1);
            $table->boolean('show_request_claim')->default(1);
            $table->boolean('show_request_unclaim')->default(1);
            $table->boolean('show_torrent_comment')->default(1);
            $table->boolean('show_torrent_tip')->default(1);
            $table->boolean('show_torrent_thank')->default(1);
            $table->boolean('show_account_follow')->default(1);
            $table->boolean('show_account_unfollow')->default(1);
        });

        Schema::table('user_notifications', function (Blueprint $table) {
            $table->index('show_torrent_comment');
            $table->index('show_subscription_forum');
            $table->index('show_torrent_thank');
            $table->index('show_following_upload');
            $table->index('show_account_unfollow');
            $table->index('show_request_comment');
            $table->index('show_bon_gift');
            $table->index('show_request_fill_approve');
            $table->index('show_mention_article_comment');
            $table->index('show_request_claim');
            $table->index('show_mention_torrent_comment');
            $table->index('show_subscription_topic');
            $table->index('show_torrent_tip');
            $table->index('show_forum_topic');
            $table->index('show_account_follow');
            $table->index('show_request_bounty');
            $table->unique('user_id', 'user_notifications_user_id_unique');
            $table->index('show_request_fill');
            $table->index('show_mention_forum_post');
            $table->index('show_request_fill_reject');
            $table->index('show_mention_request_comment');
            $table->index('show_request_unclaim');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_notifications');
    }

}