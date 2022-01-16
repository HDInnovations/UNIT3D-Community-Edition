<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use Livewire\Component;
use Livewire\WithPagination;

class MissingMediaSearch extends Component
{
    use WithPagination;

    public array $categories = [];

    public array $types = [];

    public array $resolutions = [];

    public int $perPage = 50;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    protected $queryString = [
        'categories'      => ['except' => []],
        'types'           => ['except' => []],
        'resolutions'     => ['except' => []],
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
        return Movie::with(['torrents:tmdb,type_id,resolution_id'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
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
        return view('livewire.missing-media-search', ['medias' => $this->medias]);
    }
}
