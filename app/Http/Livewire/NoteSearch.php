<?php

namespace App\Http\Livewire;

use App\Models\Note;
use Livewire\Component;
use Livewire\WithPagination;

class NoteSearch extends Component
{
    use WithPagination;

    public int $perPage = 25;

    public string $search = '';

    protected $queryString = [
        'search'  => ['except' => ''],
        'page'    => ['except' => 1],
        'perPage' => ['except' => ''],
    ];

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    final public function getNotesProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Note::query()
            ->with([
                'noteduser' => fn ($query) => $query->withTrashed()->with(['group']),
                'staffuser' => fn ($query) => $query->withTrashed()->with(['group']),
            ])
            ->when($this->search, fn ($query) => $query->where('message', 'LIKE', '%'.$this->search.'%'))
            ->latest()
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.note-search', [
            'notes' => $this->notes,
        ]);
    }

    final public function destroy(Note $note): void
    {
        $note->delete();

        $this->dispatchBrowserEvent('success', ['type' => 'success',  'message' => 'Note has successfully been deleted!']);
    }
}
