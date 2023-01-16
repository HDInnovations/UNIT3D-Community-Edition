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
use App\Models\History;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class TorrentZipController extends Controller
{
    /**
     * Show zip file containing all torrents user has history of.
     */
    public function show(Request $request, User $user): \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        //  Extend The Maximum Execution Time
        \set_time_limit(1200);

        // Authorized User
        \abort_unless($request->user()->id === $user->id, 403);

        // Define Dir For Zip
        $zipPath = \getcwd().'/files/tmp_zip/';

        // Check Directory exists
        if (! File::isDirectory($zipPath)) {
            File::makeDirectory($zipPath, 0755, true, true);
        }

        // Zip File Name
        $zipFileName = \sprintf('%s.zip', $user->username);

        // Create ZipArchive Obj
        $zipArchive = new ZipArchive();

        // Get Users History
        $historyTorrents = History::whereHas('torrent')
            ->where('user_id', '=', $user->id)
            ->pluck('torrent_id');

        if ($zipArchive->open($zipPath.'/'.$zipFileName, ZipArchive::CREATE) === true) {
            // Match History Results To Torrents
            $failCSV = '"Name","URL","ID","info_hash"
';
            $failCount = 0;
            foreach ($historyTorrents as $historyTorrent) {
                // Get Torrent
                $torrent = Torrent::withAnyStatus()
                    ->where('id', '=', $historyTorrent)
                    ->first();

                // Define The Torrent Filename
                $tmpFileName = \sprintf('%s.torrent', Str::slug($torrent->title));

                // The Torrent File Exist?
                if (! \file_exists(\getcwd().'/files/torrents/'.$torrent->file_name)) {
                    $failCSV .= '"'.$torrent->name.'","'.\route('torrent', ['id' => $torrent->id]).'","'.$torrent->id.'","'.$torrent->info_hash.'"
';
                    $failCount++;
                } else {
                    // Delete The Last Torrent Tmp File If Exist
                    if (\file_exists(\getcwd().'/files/tmp/'.$tmpFileName)) {
                        \unlink(\getcwd().'/files/tmp/'.$tmpFileName);
                    }

                    // Get The Content Of The Torrent
                    $dict = Bencode::bdecode(\file_get_contents(\getcwd().'/files/torrents/'.$torrent->file_name));
                    // Set the announce key and add the user passkey
                    $dict['announce'] = \route('announce', ['passkey' => $user->passkey]);
                    // Remove Other announce url
                    unset($dict['announce-list']);

                    $fileToDownload = Bencode::bencode($dict);
                    \file_put_contents(\getcwd().'/files/tmp/'.$tmpFileName, $fileToDownload);

                    // Add Files To ZipArchive
                    $zipArchive->addFile(\getcwd().'/files/tmp/'.$tmpFileName, $tmpFileName);
                }
            }

            if ($failCount > 0) {
                $CSVtmpName = \sprintf('%s.zip', $user->username).'-missingTorrentFiles.CSV';
                \file_put_contents(\getcwd().'/files/tmp/'.$CSVtmpName, $failCSV);
                $zipArchive->addFile(\getcwd().'/files/tmp/'.$CSVtmpName, 'missingTorrentFiles.CSV');
            }

            // Close ZipArchive
            $zipArchive->close();
        }

        $zipFile = $zipPath.'/'.$zipFileName;

        if (\file_exists($zipFile)) {
            return \response()->download($zipFile)->deleteFileAfterSend(true);
        }

        return \redirect()->back()->withErrors('Something Went Wrong!');
    }
}
