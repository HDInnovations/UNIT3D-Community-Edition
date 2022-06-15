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

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithPagination;
use SplFileInfo;

class LaravelLogViewer extends Component
{
    use WithPagination;

    public $logs = [0];

    public $page = 1;

    public $perPage = 5;

    protected $queryString = ['page'];

    final public function paginationView(): string
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingLogs(): void
    {
        $this->page = 1;
    }

    final public function getLogFilesProperty()
    {
        $directory = \storage_path('logs');

        return \collect(File::allFiles($directory))
            ->sortByDesc(fn (SplFileInfo $file) => $file->getMTime())->values();
    }

    final public function getEntriesProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $files = $this->logFiles;
        $logString = '';

        foreach ($this->logs as $log) {
            $logString .= file_get_contents($files[$log]->getPathname());
        }

        $entryPattern = '/^\[(?<date>.*)\]\s(?<env>\w+)\.(?<level>\w+)\:\s/m';
        $contextPattern = '/^(?<message>[^\{]*)?(?:\{"exception"\:"\[object\]\s\((?<exception>[^\s\(]+))?.*\s(?:in|at)\s(?<in>.*)\:(?<line>\d+)\)?/ms';

        $entries = \collect();

        if (\preg_match_all($entryPattern, $logString, $entryMatches, PREG_SET_ORDER) !== false) {
            $stacktraces = \preg_split($entryPattern, $logString);
            // Delete the empty first entry
            \array_shift($stacktraces);
            $numEntries = \count($entryMatches);

            for ($i = 0; $i < $numEntries; $i++) {
                // The context is the portion before the first stack trace
                $context = \preg_split('/^\[stacktrace\]|Stack trace\:/ms', $stacktraces[$i])[0];
                // The `context` consists of a message, an exception, a filename, and a linecount
                \preg_match($contextPattern, $context, $contextMatches);

                $entries->push([
                    'date'        => $entryMatches[$i]['date'],
                    'env'         => $entryMatches[$i]['env'],
                    'level'       => $entryMatches[$i]['level'],
                    'message'     => $contextMatches['message'] ?? '',
                    'exception'   => $contextMatches['exception'] ?? '',
                    'in'          => $contextMatches['in'] ?? '',
                    'line'        => $contextMatches['line'] ?? '',
                    'stacktrace'  => $stacktraces[$i],
                ]);
            }
        }

        $currentEntries = $entries->forPage($this->page, $this->perPage);

        return new LengthAwarePaginator($currentEntries, $entries->count(), $this->perPage, $this->page);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.laravel-log-viewer', [
            'files'   => $this->logFiles,
            'entries' => $this->entries,
        ])
            ->extends('layout.default')
            ->section('content');
    }
}
