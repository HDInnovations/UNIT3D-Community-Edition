<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Illuminate\Log\Writer;
use Illuminate\Routing\Controller;
use Illuminate\Translation\Translator;
use League\Flysystem\Adapter\Local;

final class BackupController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private Translator $translator;
    /**
     * @var \Illuminate\Filesystem\FilesystemManager
     */
    private FilesystemManager $filesystemManager;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private Factory $viewFactory;
    /**
     * @var \Illuminate\Contracts\Console\Kernel
     */
    private Kernel $kernel;
    /**
     * @var \Illuminate\Log\Writer
     */
    private Writer $logWriter;
    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    private ResponseFactory $responseFactory;

    public function __construct(Repository $configRepository, Translator $translator, FilesystemManager $filesystemManager, Factory $viewFactory, Kernel $kernel, Writer $logWriter, ResponseFactory $responseFactory)
    {
        $this->configRepository = $configRepository;
        $this->translator = $translator;
        $this->filesystemManager = $filesystemManager;
        $this->viewFactory = $viewFactory;
        $this->kernel = $kernel;
        $this->logWriter = $logWriter;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Display All Backups.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index(Request $request): Factory
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        if ((is_countable($this->configRepository->get('backup.backup.destination.disks')) ? count($this->configRepository->get('backup.backup.destination.disks')) : 0) === 0) {
            dd($this->translator->trans('backup.no_disks_configured'));
        }

        $data['backups'] = [];

        foreach ($this->configRepository->get('backup.backup.destination.disks') as $disk_name) {
            $disk = $this->filesystemManager->disk($disk_name);
            $adapter = $disk->getDriver()->getAdapter();
            $files = $disk->allFiles();

            // make an array of backup files, with their filesize and creation date
            foreach ($files as $k => $f) {
                // only take the zip files into account
                if (substr($f, -4) === '.zip' && $disk->exists($f)) {
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

        return $this->viewFactory->make('Staff.backup.index', $data);
    }

    /**
     * Create A Backup.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return string
     */
    public function create(Request $request): string
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        try {
            ini_set('max_execution_time', 900);
            // start the backup process
            $this->kernel->call('backup:run');
            $output = $this->kernel->output();

            // log the results
            $this->logWriter->info('A new backup was initiated from the staff dashboard '.$output);
            // return the results as a response to the ajax call
            echo $output;
        } catch (Exception $exception) {
            $this->responseFactory->make($exception->getMessage(), 500);
        }

        return 'success';
    }

    /**
     * Create A Backup.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return string
     */
    public function files(Request $request): string
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        try {
            ini_set('max_execution_time', 900);
            // start the backup process
            $this->kernel->call('backup:run --only-files');
            $output = $this->kernel->output();

            // log the results
            $this->logWriter->info('A new backup was initiated from the staff dashboard '.$output);
            // return the results as a response to the ajax call
            echo $output;
        } catch (Exception $exception) {
            $this->responseFactory->make($exception->getMessage(), 500);
        }

        return 'success';
    }

    /**
     * Create A Backup.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return string
     */
    public function database(Request $request): string
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        try {
            ini_set('max_execution_time', 900);
            // start the backup process
            $this->kernel->call('backup:run --only-db');
            $output = $this->kernel->output();

            // log the results
            $this->logWriter->info('A new backup was initiated from the staff dashboard '.$output);
            // return the results as a response to the ajax call
            echo $output;
        } catch (Exception $exception) {
            $this->responseFactory->make($exception->getMessage(), 500);
        }

        return 'success';
    }

    /**
     * Download A Backup.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        $disk = $this->filesystemManager->disk($request->input('disk'));
        $file_name = $request->input('file_name');
        $adapter = $disk->getDriver()->getAdapter();

        if ($adapter instanceof Local) {
            $storage_path = $disk->getDriver()->getAdapter()->getPathPrefix();

            if ($disk->exists($file_name)) {
                return $this->responseFactory->download($storage_path.$file_name);
            } else {
                return abort(404, $this->translator->trans('backup.backup_doesnt_exist'));
            }
        }

        return abort(404, $this->translator->trans('backup.only_local_downloads_supported'));
    }

    /**
     * Deletes A Backup.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return string
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_owner, 403);

        $disk = $this->filesystemManager->disk($request->input('disk'));
        $file_name = $request->input('file_name');
        $adapter = $disk->getDriver()->getAdapter();

        if ($adapter instanceof Local) {
            if ($disk->exists($file_name)) {
                $disk->delete($file_name);

                return 'success';
            } else {
                return abort(404, $this->translator->trans('backup.backup_doesnt_exist'));
            }
        }

        return abort(404, $this->translator->trans('backup.backup_doesnt_exist'));
    }
}
