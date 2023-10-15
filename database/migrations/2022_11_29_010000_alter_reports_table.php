<?php

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
        Schema::table('reports', function (Blueprint $table): void {
            $table->unsignedInteger('reported_user')->nullable()->default(null)->change();
            $table->unsignedInteger('torrent_id')->nullable()->default(null)->change();
            $table->unsignedInteger('request_id')->nullable()->default(null)->change();
        });

        DB::table('reports')->where('reported_user', '=', 0)->update([
            'reported_user' => null,
            'updated_at'    => DB::raw('updated_at'),
        ]);

        DB::table('reports')->where('torrent_id', '=', 0)->update([
            'torrent_id' => null,
            'updated_at' => DB::raw('updated_at'),
        ]);

        DB::table('reports')->where('request_id', '=', 0)->update([
            'request_id' => null,
            'updated_at' => DB::raw('updated_at'),
        ]);
    }
};
