<?php

use App\Helpers\Bencode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('torrents', function (Blueprint $table): void {
            $table->string('folder')->nullable()->after('num_file');
        });

        $directory = public_path().'/files/torrents/';

        DB::table('torrents')
            ->lazyById()
            ->each(function (object $torrent) use ($directory): void {
                if (file_exists($directory.$torrent->file_name)) {
                    $dict = Bencode::bdecode_file($directory.$torrent->file_name);

                    DB::table('torrents')
                        ->where('id', $torrent->id)
                        ->update([
                            'folder' => Bencode::get_name($dict),
                        ]);
                }
            });
    }
};
