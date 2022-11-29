<?php

use App\Models\Torrent;
use App\Models\TorrentBdinfo;
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
        Schema::create('torrent_bdinfos', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->index();
            $table->text('bdinfo');
        });

        $torrents = Torrent::withAnyStatus()
            ->whereNotNull('bdinfo')
            ->select(['id', 'bdinfo'])
            ->get();

        foreach ($torrents as $torrent) {
            $torrentBdinfo = new TorrentBdinfo();
            $torrentBdinfo->torrent_id = $torrent->id;
            $torrentBdinfo->bdinfo = $torrent->bdinfo;
            $torrentBdinfo->save();
        }

        Schema::table('torrents', function (Blueprint $table) {
            $table->dropColumn('bdinfo');
        });
    }
};
