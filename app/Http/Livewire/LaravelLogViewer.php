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

namespace App\Http\Livewire;

use Illuminate\Support\Facades\File;
use Livewire\Component;
use SplFileInfo;

class LaravelLogViewer extends Component
{
    public $file = 0;

    public $page = 1;

    public $total;

    public $perPage = 500;

    public $paginator;

    protected $queryString = ['page'];

    final protected function getLogFiles()
    {
        $directory = \storage_path('logs');

        return \collect(File::allFiles($directory))
            ->sortByDesc(fn (SplFileInfo $file) => $file->getMTime())->values();
    }

    final public function goto($page): void
    {
        $this->page = $page;
    }

    final public function updatingFile(): void
    {
        $this->page = 1;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $files = $this->getLogFiles();

        $log = \collect(file($files[$this->file]->getPathname(), FILE_IGNORE_NEW_LINES));

        $this->total = (int) \floor($log->count() / $this->perPage) + 1;

        $log = $log->slice(($this->page - 1) * $this->perPage, $this->perPage)->values();

        return \view('livewire.laravel-log-viewer')
            ->withFiles($files)
            ->withLog($log)
            ->extends('layout.default');
    }
}
