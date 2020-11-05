<?php

use App\Models\Torrent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RatioMulti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("torrents", function (Blueprint $table) {
            $table->decimal("multi_up", 5, 2)->default(1.0)->nullable(false);
            $table->decimal("multi_down", 5, 2)->default(1.0)->nullable(false);
        });
        try {
            DB::beginTransaction();
            foreach (Torrent::all() as $torrent) {
                $torrent->multi_up = $torrent->doubleup === 1 ? 2.0 : 1.0;
                $torrent->multi_down = $torrent->free === 1 ? 0.0 : 1.0;
                $torrent->save();
            }
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            throw $e;
        }
        Schema::table("torrents", function(Blueprint $table) {
            $table->dropColumn(["free", "doubleup"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("torrents", function (Blueprint $table) {
            $table->boolean("free")->default(false)->nullable(false);
            $table->boolean("doubleup")->default(false)->nullable(false);
        });
        try {
            DB::beginTransaction();
            foreach (Torrent::all() as $torrent) {
                $torrent->doubleup = $torrent->multi_up >= 2 ? 1 : 0;
                $torrent->free = $torrent->multi_dn === 0 ? 1 : 0;
                $torrent->save();
            }
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            throw $e;
        }
        Schema::table("torrents", function(Blueprint $table) {
            $table->dropColumn(["multi_up", "multi_down"]);
        });
    }
}
