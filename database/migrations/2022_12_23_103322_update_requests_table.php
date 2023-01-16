<?php

use App\Models\TorrentRequest;
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
        Schema::table('requests', function (Blueprint $table) {
            $table->unsignedInteger('torrent_id')->nullable()->after('filled_hash');

            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        TorrentRequest::query()
            ->join('torrents', 'requests.filled_hash', 'torrents.info_hash')
            ->update([
                'torrent_id' => DB::raw('torrents.id'),
                'updated_at' => DB::raw('requests.updated_at'),
            ]);

        Schema::table('requests', function (Blueprint $table) {
            $table->dropIndex('filled_hash');
            $table->dropColumn('filled_hash');
        });
    }
};
