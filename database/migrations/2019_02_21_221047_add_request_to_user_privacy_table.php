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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_privacy', function (Blueprint $table) {
            $table->boolean('show_profile_request_extra')->index()->default(1)->after('show_profile_forum_extra');
            $table->json('json_request_groups')->after('json_rank_groups');
            $table->json('json_other_groups')->after('json_request_groups');
            $table->boolean('show_online')->index()->default(1)->after('show_follower');
            $table->boolean('show_peer')->index()->default(1)->after('show_online');
            $table->boolean('show_requested')->index()->default(1)->after('show_rank');
        });
    }
};
