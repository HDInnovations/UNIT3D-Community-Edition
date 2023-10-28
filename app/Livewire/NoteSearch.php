<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class NoteSearch extends Component
{
    use WithPagination;

    #[Url]
    public int $perPage = 25;

    #[Url]
    public string $search = '';

    final public function updatedPage(): void
    {
        $this->dispatch('paginationChanged');
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

        $this->dispatch('success', type: 'success', message: 'Note has successfully been deleted!');
    }
}
