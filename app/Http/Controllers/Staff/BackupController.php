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

namespace App\Http\Controllers\Staff;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Adapter\Local;

class BackupController extends Controller
{
    /**
     * Display All Backups.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return
     */
    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        if (!(is_countable(config('backup.backup.destination.disks')) ? count(config('backup.backup.destination.disks')) : 0)) {
            dd(trans('backup.no_disks_configured'));
        }

        $data['backups'] = [];

        foreach (config('backup.backup.destination.disks') as $disk_name) {
            $disk = Storage::disk($disk_name);
            $adapter = $disk->getDriver()->getAdapter();
            $files = $disk->allFiles();

            // make an array of backup files, with their filesize and creation date
            foreach ($files as $k => $f) {
                // only take the zip files into account
                if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                    $data['backups'][] = [
                        'file_path'     => $f,
                        'file_name'     => str_replace('backups/', '', $f),
                        'file_size'     => $disk->size($f),
                        'last_modified' => $disk->lastModified($f),
                        'disk'          => $disk_name,
                        'download'      => $adapter instanceof Local,
                    ];
                }
            }
        }

        // reverse the backups, so the newest one would be on top
        $data['backups'] = array_reverse($data['backups']);
        $data['title'] = 'Backups';

        return view('Staff.backup.index', $data);
    }

    /**
     * Create A Backup.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function create(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        try {
            ini_set('max_execution_time', 900);
            // start the backup process
            Artisan::call('backup:run');
            $output = Artisan::output();

            // log the results
            info('A new backup was initiated from the staff dashboard '.$output);
            // return the results as a response to the ajax call
            echo $output;
        } catch (Exception $e) {
            response($e->getMessage(), 500);
        }

        return 'success';
    }

    /**
     * Create A Backup.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function files(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        try {
            ini_set('max_execution_time', 900);
            // start the backup process
            Artisan::call('backup:run --only-files');
            $output = Artisan::output();

            // log the results
            info('A new backup was initiated from the staff dashboard '.$output);
            // return the results as a response to the ajax call
            echo $output;
        } catch (Exception $e) {
            response($e->getMessage(), 500);
        }

        return 'success';
    }

    /**
     * Create A Backup.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function database(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        try {
            ini_set('max_execution_time', 900);
            // start the backup process
            Artisan::call('backup:run --only-db');
            $output = Artisan::output();

            // log the results
            info('A new backup was initiated from the staff dashboard '.$output);
            // return the results as a response to the ajax call
            echo $output;
        } catch (Exception $e) {
            response($e->getMessage(), 500);
        }

        return 'success';
    }

    /**
     * Download A Backup.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return
     */
    public function download(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        $disk = Storage::disk($request->input('disk'));
        $file_name = $request->input('file_name');
        $adapter = $disk->getDriver()->getAdapter();

        if ($adapter instanceof Local) {
            $storage_path = $disk->getDriver()->getAdapter()->getPathPrefix();

            if ($disk->exists($file_name)) {
                return response()->download($storage_path.$file_name);
            }

            return abort(404, trans('backup.backup_doesnt_exist'));
        }

        return abort(404, trans('backup.only_local_downloads_supported'));
    }

    /**
     * Deletes A Backup.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        $disk = Storage::disk($request->input('disk'));
        $file_name = $request->input('file_name');
        $adapter = $disk->getDriver()->getAdapter();

        if ($adapter instanceof Local) {
            if ($disk->exists($file_name)) {
                $disk->delete($file_name);

                return 'success';
            }

            return abort(404, trans('backup.backup_doesnt_exist'));
        }

        return abort(404, trans('backup.backup_doesnt_exist'));
    }
}
