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

class CreateUserPrivacyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_privacy', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('show_achievement')->default(1);
            $table->boolean('show_bon')->default(1);
            $table->boolean('show_comment')->default(1);
            $table->boolean('show_download')->default(0);
            $table->boolean('show_follower')->default(1);
            $table->boolean('show_online')->default(1);
            $table->boolean('show_peer')->default(1);
            $table->boolean('show_post')->default(1);
            $table->boolean('show_profile')->default(1);
            $table->boolean('show_profile_about')->default(1);
            $table->boolean('show_profile_achievement')->default(1);
            $table->boolean('show_profile_badge')->default(1);
            $table->boolean('show_profile_follower')->default(1);
            $table->boolean('show_profile_title')->default(1);
            $table->boolean('show_profile_bon_extra')->default(1);
            $table->boolean('show_profile_comment_extra')->default(1);
            $table->boolean('show_profile_forum_extra')->default(1);
            $table->boolean('show_profile_request_extra')->default(1);
            $table->boolean('show_profile_torrent_count')->default(1);
            $table->boolean('show_profile_torrent_extra')->default(1);
            $table->boolean('show_profile_torrent_ratio')->default(1);
            $table->boolean('show_profile_torrent_seed')->default(1);
            $table->boolean('show_profile_warning')->default(1);
            $table->boolean('show_rank')->default(1);
            $table->boolean('show_requested')->default(1);
            $table->boolean('show_topic')->default(1);
            $table->boolean('show_upload')->default(0);
            $table->boolean('show_wishlist')->default(1);
        });

        Schema::table('user_privacy', function (Blueprint $table) {
            $table->index('show_profile_torrent_extra');
            $table->index('show_profile');
            $table->index('show_profile_torrent_seed');
            $table->index('show_profile_achievement');
            $table->index('show_rank');
            $table->index('show_profile_follower');
            $table->index('show_achievement');
            $table->index('show_profile_bon_extra');
            $table->index('show_comment');
            $table->index('show_profile_forum_extra');
            $table->index('show_follower');
            $table->index('show_topic');
            $table->index('show_wishlist');
            $table->index('show_online');
            $table->index('show_requested');
            $table->index('show_post');
            $table->index('show_profile_torrent_ratio');
            $table->index('show_profile_about');
            $table->index('show_profile_warning');
            $table->index('show_profile_badge');
            $table->unique('user_id', 'user_privacy_user_id_unique');
            $table->index('show_profile_title');
            $table->index('show_bon');
            $table->index('show_profile_comment_extra');
            $table->index('show_download');
            $table->index('show_profile_torrent_count');
            $table->index('show_upload');
            $table->index('show_profile_request_extra');
            $table->index('show_peer');

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
        Schema::dropIfExists('user_privacy');
    }
}