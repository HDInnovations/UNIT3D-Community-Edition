<?php

namespace App\Livewire;

use App\Models\Movie;
use App\Models\Type;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MissingMediaSearch extends Component
{
    use WithPagination;

    #[Url]
    public array $categories = [];

    #[Url]
    public int $perPage = 50;

    #[Url]
    public string $sortField = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Movie>
     */
    #[Computed]
    final public function medias(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Movie::with(['torrents:tmdb,resolution_id,type_id' => ['resolution:id,position,name']])
            ->withCount(['requests' => fn ($query) => $query->whereNull('torrent_id')->whereNull('claimed')])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Type>
     */
    #[Computed]
    final public function types(): \Illuminate\Database\Eloquent\Collection
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
