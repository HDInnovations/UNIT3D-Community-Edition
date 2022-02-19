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
        Schema::create('comments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('content', 65535);
            $table->smallInteger('anon')->default(0);
            $table->bigInteger('torrent_id')->unsigned()->nullable()->index('fk_comments_torrents_1');
            $table->integer('article_id')->nullable()->index('fk_comments_articles_1');
            $table->integer('requests_id')->nullable();
            $table->integer('user_id')->nullable()->index('fk_comments_users_1');
            $table->timestamps();
        });
    }
};
