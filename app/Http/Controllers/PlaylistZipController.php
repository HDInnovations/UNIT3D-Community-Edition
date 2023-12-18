<?php
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

namespace App\Http\Controllers;

use App\Helpers\Bencode;
use App\Models\Playlist;
use Illuminate\Support\Facades\File;
use ZipArchive;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\PlaylistControllerTest
 */
class PlaylistZipController extends Controller
{
    /**
     * Download All Playlist Torrents.
     */
    public function show(Playlist $playlist): \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        //  Extend The Maximum Execution Time
        set_time_limit(300);

        // Playlist
        $playlist->load('torrents');

        // Authorized User
        $user = auth()->user();

        // Define Dir Folder
        $path = getcwd().'/files/tmp_zip/';

        // Check Directory exists
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        // Zip File Name
        $zipFileName = '['.$user->username.']'.$playlist->name.'.zip';

        // Create ZipArchive Obj
        $zipArchive = new ZipArchive();

        if ($zipArchive->open($path.$zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $announceUrl = route('announce', ['passkey' => $user->passkey]);

            foreach ($playlist->torrents()->get() as $torrent) {
                $dict = Bencode::bdecode(file_get_contents(getcwd().'/files/torrents/'.$torrent->file_name));

                // Set the announce key and add the user passkey
                $dict['announce'] = $announceUrl;

                // Set link to torrent as the comment
                if (config('torrent.comment')) {
                    $dict['comment'] = config('torrent.comment').'. '.route('torrents.show', ['id' => $torrent->id]);
                } else {
                    $dict['comment'] = route('torrents.show', ['id' => $torrent->id]);
                }

                $fileToDownload = Bencode::bencode($dict);

                $filename = str_replace([' ', '/', '\\'], ['.', '-', '-'], '['.config('torrent.source').']'.$torrent->name.'.torrent');

                $zipArchive->addFromString($filename, $fileToDownload);
            }

            $zipArchive->close();
        }

        if (file_exists($path.$zipFileName)) {
            return response()->download($path.$zipFileName)->deleteFileAfterSend(true);
        }

        return redirect()->back()->withErrors(trans('common.something-went-wrong'));
    }
}
