<?php

use App\Models\BonTransactions;
use App\Models\Bookmark;
use App\Models\FeaturedTorrent;
use App\Models\FreeleechToken;
use App\Models\Graveyard;
use App\Models\History;
use App\Models\Keyword;
use App\Models\Peer;
use App\Models\PlaylistTorrent;
use App\Models\Report;
use App\Models\Subtitle;
use App\Models\Thank;
use App\Models\Torrent;
use App\Models\TorrentDownload;
use App\Models\TorrentFile;
use App\Models\Warning;
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
        $torrentIds = Torrent::withoutGlobalScopes()->pluck('id');

        BonTransactions::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        Bookmark::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        FeaturedTorrent::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        TorrentFile::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        FreeleechToken::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        DB::table('genre_torrent')
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        Graveyard::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        History::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        Keyword::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        Peer::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        PlaylistTorrent::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        Report::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        Subtitle::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        Thank::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        TorrentDownload::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent_id', $torrentIds)
            ->whereNotNull('torrent_id')
            ->delete();

        Warning::withoutGlobalScopes()
            ->whereIntegerNotInRaw('torrent', $torrentIds)
            ->whereNotNull('torrent')
            ->delete();

        // Remove constraint
        Schema::table('warnings', function (Blueprint $table) {
            $table->dropForeign(['torrent']);
        });

        Schema::table('peers', function (Blueprint $table) {
            $table->dropForeign('fk_peers_torrents1');
        });

        // Alter column type, remove indexes, and add foreign key constraint
        Schema::table('torrents', function (Blueprint $table) {
            $table->increments('id')->change();
        });

        Schema::table('bon_transactions', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->nullable()->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('featured_torrents', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('files', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex('fk_files_torrents1_idx');
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('freeleech_tokens', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('genre_torrent', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('graveyard', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('history', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('keywords', function (Blueprint $table) {
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('peers', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->nullable()->change();
            $table->dropIndex('fk_peers_torrents1_idx');
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('playlist_torrents', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->default('0')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('subtitles', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('thanks', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('torrent_downloads', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->change();
            $table->dropIndex(['torrent_id']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('warnings', function (Blueprint $table) {
            $table->unsignedInteger('torrent')->nullable()->change();
            $table->foreign('torrent')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
};
