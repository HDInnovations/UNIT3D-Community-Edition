<?php

use App\Models\Torrent;
use App\Models\TorrentMediainfo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torrent_mediainfos', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->index();
            $table->text('mediainfo');
        });

        $torrents = Torrent::withAnyStatus()
            ->whereNotNull('mediainfo')
            ->select(['id', 'mediainfo'])
            ->get();

        foreach ($torrents as $torrent) {
            $torrentMediainfo = new TorrentMediainfo();
            $torrentMediainfo->torrent_id = $torrent->id;
            $torrentMediainfo->mediainfo = $torrent->mediainfo;
            $torrentMediainfo->save();
        }

        Schema::table('torrents', function (Blueprint $table) {
            $table->dropColumn('mediainfo');
        });
    }
};
