<?php

use App\Models\TorrentRequest;
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
        TorrentRequest::query()
            ->whereNull('imdb')
            ->orWhere('imdb', '<', 0)
            ->orWhere('imdb', '>', 2_000_000_000)
            ->orWhere('imdb', 'not regex', '\d+')
            ->update(['imdb' => '0']);

        TorrentRequest::query()
            ->whereNull('tmdb')
            ->orWhere('tmdb', '<', 0)
            ->orWhere('tmdb', '>', 2_000_000_000)
            ->orWhere('tmdb', 'not regex', '\d+')
            ->update(['tmdb' => '0']);

        TorrentRequest::query()
            ->whereNull('tvdb')
            ->orWhere('tvdb', '<', 0)
            ->orWhere('tvdb', '>', 2_000_000_000)
            ->orWhere('tvdb', 'not regex', '\d+')
            ->update(['tvdb' => '0']);

        TorrentRequest::query()
            ->whereNull('mal')
            ->orWhere('mal', '<', 0)
            ->orWhere('mal', '>', 2_000_000_000)
            ->orWhere('mal', 'not regex', '\d+')
            ->update(['mal' => '0']);

        Schema::table('requests', function (Blueprint $table) {
            $table->integer('imdb')->unsigned()->change();
            $table->integer('tvdb')->unsigned()->change();
            $table->integer('tmdb')->unsigned()->change();
            $table->integer('mal')->unsigned()->change();
        });
    }
};
