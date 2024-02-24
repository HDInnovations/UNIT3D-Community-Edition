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

namespace App\Http\Livewire;

use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use App\Models\User;
use App\Traits\LivewireSort;
use Livewire\Component;
use Livewire\WithPagination;

class UserUploads extends Component
{
    use LivewireSort;
    use WithPagination;

    public ?User $user = null;

    public int $perPage = 25;

    public string $name = '';

    public string $personalRelease = 'any';

    /**
     * @var string[]
     */
    public array $status = [];

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public bool $showMorePrecision = false;

    /**
     * @var array<mixed>
     */
    protected $queryString = [
        'perPage'         => ['except' => ''],
        'name'            => ['except' => ''],
        'personalRelease' => ['except' => 'any'],
        'sortField'       => ['except' => 'created_at'],
        'sortDirection'   => ['except' => 'desc'],
        'status'          => ['except' => []],
    ];

    final public function mount(int $userId): void
    {
        $this->user = User::find($userId);
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Torrent>
     */
    final public function getUploadsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $uploads = Torrent::query()
            ->withCount('thanks')
            ->withSum('tips', 'cost')
            ->withoutGlobalScope(ApprovedScope::class)
            ->where('created_at', '>=', $this->user->created_at) // Unneeded, but increases performances
            ->where('user_id', '=', $this->user->id)
            ->when(
                $this->name,
                fn ($query) => $query
                    ->where('name', 'like', '%'.str_replace(' ', '%', $this->name).'%')
            )
            ->when(!empty($this->status), fn ($query) => $query->whereIntegerInRaw('status', $this->status))
            ->when($this->personalRelease === 'include', fn ($query) => $query->where('personal_release', '=', 1))
            ->when($this->personalRelease === 'exclude', fn ($query) => $query->where('personal_release', '=', 0))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return $uploads->setCollection($uploads->getCollection()->groupBy(fn ($torrent) => $torrent->created_at->format('Y-m')));
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.user-uploads', [
            'uploads' => $this->uploads,
        ]);
    }
}
