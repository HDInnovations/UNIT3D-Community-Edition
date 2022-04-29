<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use App\Models\Person;
use App\Models\Tv;
use Livewire\Component;

class QuickSearchDropdown extends Component
{
    public string $quicksearchRadio = 'movies';

    public string $quicksearchText = '';

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $search_results = match ($this->quicksearchRadio) {
            'movies' => Movie::query()
                ->select(['id', 'poster', 'title', 'release_date'])
                ->where('title', 'LIKE', '%'.$this->quicksearchText.'%')
                ->oldest('title')
                ->take(10)
                ->get(),
            'series' => Tv::query()
                ->select(['id', 'poster', 'name', 'first_air_date'])
                ->where('name', 'LIKE', '%'.$this->quicksearchText.'%')
                ->oldest('name')
                ->take(10)
                ->get(),
            'persons' => Person::query()
                ->select(['id', 'still', 'name'])
                ->whereNotNull('still')
                ->where('name', 'LIKE', '%'.$this->quicksearchText.'%')
                ->oldest('name')
                ->take(10)
                ->get(),
            default  => [],
        };

        return \view('livewire.quick-search-dropdown', [
            'search_results' => $search_results,
        ]);
    }
}
