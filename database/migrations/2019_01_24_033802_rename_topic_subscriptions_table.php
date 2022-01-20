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

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('topic_subscriptions', 'subscriptions');
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropUnique('topic_subscriptions_user_id_topic_id_unique');
            $table->integer('forum_id')->nullable()->index()->after('user_id');
            $table->integer('user_id')->change();
            $table->integer('topic_id')->nullable()->change();
            $table->index('user_id');
            $table->index('topic_id');
        });
    }
};
