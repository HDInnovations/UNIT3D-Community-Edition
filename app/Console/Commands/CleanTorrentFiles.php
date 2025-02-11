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
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Console\Commands;

use App\Helpers\Bencode;
use App\Models\Torrent;
use Exception;
use Illuminate\Console\Command;
use Throwable;

class CleanTorrentFiles extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'clean:torrent_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans torrent files to remove extra unneeded data';

    /**
     * Execute the console command.
     *
     * @throws Exception|Throwable If there is an error during the execution of the command.
     */
    final public function handle(): void
    {
        $this->alert('Torrent file cleaning started');

        $directory = public_path('/files/torrents/');

        Torrent::withoutGlobalScopes()->select('file_name')->orderBy('id')->chunk(100, function ($torrents) use ($directory): void {
            foreach ($torrents as $torrent) {
                $filePath = $directory.$torrent->file_name;

                if (file_exists($filePath)) {
                    $dict = Bencode::bdecode_file($filePath);

                    // Whitelisted keys
                    $dict = array_intersect_key($dict, [
                        'created by' => '',
                        'encoding'   => '',
                        'info'       => '',
                    ]);

                    file_put_contents($filePath, Bencode::bencode($dict));
                }
            }
        });

        $this->alert('Torrent file cleaning complete');
    }
}
