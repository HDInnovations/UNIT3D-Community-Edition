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

use App\Models\Audit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use JsonException;

class AuditLogSearch extends Component
{
    use WithPagination;

    #[Url]
    public string $username = '';

    #[Url]
    public string $modelName = '';

    #[Url]
    public string $modelId = '';

    #[Url]
    public string $action = '';

    #[Url]
    public string $record = '';

    #[Url]
    public int $perPage = 25;

    #[Url]
    public string $sortField = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    /**
     * @return string[]
     */
    #[Computed]
    final public function modelNames(): array
    {
        $modelList = [];
        $path = app_path().'/Models';
        $results = scandir($path);

        foreach ($results as $result) {
            if ($result === '.' || $result === '..') {
                continue;
            }
            $filename = $result;

            $modelList[] = substr($filename, 0, -4);
        }

        return $modelList;
    }

    /**
     * @throws JsonException
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Audit>
     */
    #[Computed]
    final public function audits(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $audits = Audit::with('user')
            ->when($this->username, fn ($query) => $query->whereRelation('user', 'username', '=', $this->username))
            ->when($this->modelName, fn ($query) => $query->where('model_name', '=', $this->modelName))
            ->when($this->modelId, fn ($query) => $query->where('model_entry_id', '=', $this->modelId))
            ->when($this->action, fn ($query) => $query->where('action', '=', $this->action))
            ->when($this->record, fn ($query) => $query->where('record', 'LIKE', '%'.$this->record.'%'))
            ->latest()
            ->paginate($this->perPage);

        foreach ($audits as $audit) {
            $audit->values = json_decode((string) $audit->record, true, 512, JSON_THROW_ON_ERROR);
        }

        return $audits;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.audit-log-search', [
            'audits'     => $this->audits,
            'modelNames' => $this->modelNames,
        ]);
    }
}
