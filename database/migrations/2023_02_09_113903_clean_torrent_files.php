<?php

use App\Helpers\Bencode;
use App\Models\Torrent;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $directory = public_path().'/files/torrents/';

        Torrent::select('file_name')->orderBy('id')->chunk(100, function ($torrents) use ($directory): void {
            foreach ($torrents as $torrent) {
                if (file_exists($directory.$torrent->file_name)) {
                    $dict = Bencode::bdecode_file($directory.$torrent->file_name);

                    // Whitelisted keys
                    $dict = array_intersect_key($dict, [
                        'announce'   => '',
                        'comment'    => '',
                        'created by' => '',
                        'encoding'   => '',
                        'info'       => '',
                    ]);

                    $dict['announce'] = config('app.url').'/announce/PID';

                    $comment = config('torrent.comment', null);
                    if ($comment !== null) {
                        $result['comment'] = $comment;
                    }

                    file_put_contents($directory.$torrent->file_name, Bencode::bencode($dict));
                }
            }
        });
    }
};
