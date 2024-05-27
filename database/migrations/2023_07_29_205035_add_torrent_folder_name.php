<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

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
