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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $torrentIds = DB::table('torrents')->pluck('id');

        DB::table('bon_transactions')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('bookmarks')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('featured_torrents')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('files')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('freeleech_tokens')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('genre_torrent')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('graveyard')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('history')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('keywords')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('peers')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('playlist_torrents')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('reports')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('subtitles')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('thanks')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('torrent_downloads')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('warnings')
            ->whereIntegerNotInRaw('torrent', $torrentIds)
            ->whereNotNull('torrent')
            ->delete();

        // Remove constraint
        Schema::table('warnings', function (Blueprint $table): void {
            $table->dropForeign(['torrent']);
        });

        Schema::table('peers', function (Blueprint $table): void {
            $table->dropForeign('fk_peers_torrents1');
        });

        // Alter column type, remove indexes, and add foreign key constraint
        Schema::table('torrents', function (Blueprint $table): void {
            $table->increments('id')->change();
        });

        Schema::table('bon_transactions', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->nullable()->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('bookmarks', function (Blueprint $table): void {
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('featured_torrents', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('files', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex('fk_files_torrents1_idx');
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('freeleech_tokens', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('genre_torrent', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('graveyard', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('history', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('keywords', function (Blueprint $table): void {
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('peers', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->nullable()->change();
            $table->dropIndex('fk_peers_torrents1_idx');
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('playlist_torrents', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->default('0')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('reports', function (Blueprint $table): void {
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('subtitles', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('thanks', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('torrent_downloads', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('warnings', function (Blueprint $table): void {
            $table->unsignedInteger('torrent')->nullable()->change();
            $table->foreign('torrent')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
};
