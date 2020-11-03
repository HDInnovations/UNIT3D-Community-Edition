<?php

namespace App\Http\Livewire;

use App\Models\Torrent;
use App\Models\Subtitle;
use Livewire\Component;
use Livewire\WithPagination;

class SubtitleSearch extends Component
{
    use WithPagination;

    public $perPage = 25;
    public $searchTerm = '';
    public $categories = [];
    public $language = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function paginationView()
    {
        return 'vendor.pagination.livewire-pagination';
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $subtitles = Subtitle::with(['user', 'torrent', 'language'])
            ->when($this->searchTerm, function ($query) {
                return $query->where('title', 'like', '%'.$this->searchTerm.'%');
            })
            ->when($this->categories, function ($query) {
                $torrents = Torrent::whereIn('category_id', $this->categories)->pluck('id');
                return $query->whereIn('torrent_id', $torrents);
            })
            ->when($this->language, function ($query) {
                return $query->where('language_id', '=', $this->language);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.subtitle-search', [
            'subtitles' => $subtitles
        ]);
    }
}
