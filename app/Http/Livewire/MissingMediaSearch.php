<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use App\Models\Type;
use Livewire\Component;
use Livewire\WithPagination;

class MissingMediaSearch extends Component
{
    use WithPagination;

    public array $categories = [];

    public int $perPage = 50;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    protected $queryString = [
        'categories'      => ['except' => []],
        'sortField'       => ['except' => 'created_at'],
        'sortDirection'   => ['except' => 'desc'],
        'page'            => ['except' => 1],
        'perPage'         => ['except' => ''],
    ];

    final public function paginationView(): string
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function getMediasProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Movie::with(['torrents:tmdb,resolution_id,type_id' => ['resolution:id,position,name']])
            ->withCount(['requests' => fn ($query) => $query->whereNull('torrent_id')->whereNull('claimed')])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function getTypesProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return Type::select('id', 'position', 'name')->orderBy('position')->get();
    }

    final public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.missing-media-search', ['medias' => $this->medias, 'types' => $this->types]);
    }
}
