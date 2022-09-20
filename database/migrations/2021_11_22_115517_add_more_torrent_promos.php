<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('torrents', function (Blueprint $table) {
            $table->smallInteger('free')->default(0)->change();
        });

        // Change all "free->1" torrents to "free->100" for now FL discounts
        $fl_torrents = DB::table('torrents')->select('id', 'free')->where('free', '=', 1)->get();
        $i = 0;
        foreach ($fl_torrents as $torrent) {
            DB::table('torrents')
                ->where('id', $torrent->id)
                ->update([
                    'free' => '100',
                ]);
            $i++;
        }
    }

    public function down(): void
    {
        Schema::table('torrents', function (Blueprint $table) {
            $table->boolean('free')->default(0)->change();
        });

        // Change all "free->100" torrents to "free->1" for now FL discounts
        $fl_torrents = DB::table('torrents')->select('id', 'free')->where('free', '>', 1)->get();
        $i = 0;
        foreach ($fl_torrents as $torrent) {
            DB::table('torrents')
                ->where('id', $torrent->id)
                ->update([
                    'free' => '1',
                ]);
            $i++;
        }
    }
};
