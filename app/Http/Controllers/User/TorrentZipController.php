<?php
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

namespace App\Http\Controllers\User;

use App\Helpers\Bencode;
use App\Http\Controllers\Controller;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use ZipArchive;

class TorrentZipController extends Controller
{
    /**
     * Show zip file containing all torrents user has history of.
     */
    public function show(Request $request, User $user): \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        //  Extend The Maximum Execution Time
        set_time_limit(1200);

        // Extend The Maximum Memory Limit
        ini_set('memory_limit', '1024M');

        // Authorized User
        abort_unless($request->user()->is($user), 403);

        // Get Users History Or Peers
        if ($request->boolean('type') === true) {
            $torrents = Torrent::whereRelation('peers', 'user_id', '=', $user->id)
                ->whereRelation('peers', 'active', '=', true)
                ->whereRelation('peers', 'visible', '=', true)
                ->get();

            $zipFileName = $user->username.'-peers'.'.zip';
        } else {
            $torrents = Torrent::whereRelation('history', 'user_id', '=', $user->id)->get();

            $zipFileName = $user->username.'-history'.'.zip';
        }

        if ($torrents->isEmpty()) {
            return redirect()->back()->withErrors('No Torrents Found');
        }

        // Define Dir For Zip
        $zipPath = getcwd().'/files/tmp_zip/';

        // Check Directory exists
        if (!File::isDirectory($zipPath)) {
            File::makeDirectory($zipPath, 0755, true, true);
        }

        // Create ZipArchive Obj
        $zipArchive = new ZipArchive();

        if ($zipArchive->open($zipPath.$zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $announceUrl = route('announce', ['passkey' => $user->passkey]);

            foreach ($torrents as $torrent) {
                if (file_exists(getcwd().'/files/torrents/'.$torrent->file_name)) {
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

                    $filename = str_replace(
                        [' ', '/', '\\'],
                        ['.', '-', '-'],
                        '['.config('torrent.source').']'.$torrent->name.'.torrent'
                    );

                    $zipArchive->addFromString($filename, $fileToDownload);
                }
            }

            $zipArchive->close();
        }

        if (file_exists($zipPath.$zipFileName)) {
            return response()->download($zipPath.$zipFileName)->deleteFileAfterSend(true);
        }

        return redirect()->back()->withErrors(trans('common.something-went-wrong'));
    }
}
