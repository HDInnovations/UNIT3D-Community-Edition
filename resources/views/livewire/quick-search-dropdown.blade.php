<div style="width: 460px; min-height: 50px; display: block;" x-data="{ lock: false }">
    <div class="text-center form-inline"
         @focusout="if (!lock) { $wire.set('movie', ''); $wire.set('series', ''); $wire.set('person', ''); }">
        <div class="form-group" style="position: relative; vertical-align: top;">
            <input wire:model.debounce.250ms="movie" type="text" class="form-control" placeholder="Movie"
                   autocomplete="off" style="width: 150px;"
                   @focusin="$wire.set('series', ''); $wire.set('person', '');">
        </div>
        <div class="form-group" style="position: relative; vertical-align: top;">
            <input wire:model.debounce.250ms="series" type="text" class="form-control" placeholder="Series"
                   autocomplete="off" style="width: 150px;" @focusin="$wire.set('movie', ''); $wire.set('person', '');">
        </div>
        <div class="form-group" style="position: relative; vertical-align: top;">
            <input wire:model.debounce.250ms="person" type="text" class="form-control" placeholder="Person"
                   autocomplete="off" style="width: 150px;" @focusin="$wire.set('movie', ''); $wire.set('series', '');">
        </div>
    </div>
    @if( strlen($movie) > 2  || strlen($series) > 2  || strlen($person) > 2)
        <div style="width: 100%; min-height: 60px; margin: 0; padding: 3px; display: block; background-color: #2b2b2b;"
             @mouseenter="lock = true;" @mouseleave="lock = false">
            @forelse ($search_results as $search_result)
                @if (strlen($movie) > 2 )
                    <div id="movie.{{$search_result->id}}" class="row" style="cursor: pointer; margin-bottom: 5px;"
                         @click="window.location.href = '{{ route('torrents.similar', ['category_id' => '1', 'tmdb' => $search_result->id]) }}'"
                         @contextmenu.prevent="window.open('{{ route('torrents.similar', ['category_id' => '1', 'tmdb' => $search_result->id]) }}', '_blank').focus()"


                    >
                        <div class="col-xs-3 text-center">
                            <img src="{{ $search_result->poster }}" style="height: 60px; padding: 0; margin: 0">
                        </div>
                        <div class="col-xs-9 text-left">
                            <div style="height: 60px;">
                                <p style="line-height: initial; height: 60px; display: table-cell; vertical-align: middle;">
                                    {{ $search_result->title }} ({{ substr($search_result->release_date, 0, 4) }})
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif (strlen($series) > 2 )
                    <div id="series.{{$search_result->id}}" class="row" style="cursor: pointer; margin-bottom: 5px;"
                         @click="window.location.href = '{{ route('torrents.similar', ['category_id' => '2', 'tmdb' => $search_result->id]) }}'"
                         @contextmenu.prevent="window.open('{{ route('torrents.similar', ['category_id' => '2', 'tmdb' => $search_result->id]) }}', '_blank').focus()"
                    >
                        <div class="col-xs-3 text-center">
                            <img src="{{ $search_result->poster }}" style="height: 60px; padding: 0; margin: 0">
                        </div>
                        <div class="col-xs-9 text-left">
                            <div style="height: 60px;">
                                <p style="line-height: initial; height: 60px; display: table-cell; vertical-align: middle;">{{ $search_result->name }}
                                    ({{ substr($search_result->first_air_date, 0, 4) }})
                                </p>
                            </div>
                        </div>
                    </div>

                @elseif (strlen($person) > 2 )
                    <div id="person.{{$search_result->id}}" class="row" style="cursor: pointer; margin-bottom: 5px;"
                         @click="window.location.href = '{{ route('mediahub.persons.show', ['id' => $search_result->id]) }}'"
                         @contextmenu.prevent="window.open('{{ route('mediahub.persons.show', ['id' => $search_result->id]) }}', '_blank').focus()"
                    >
                        <div class="col-xs-3 text-center">
                            <img src="{{ $search_result->still }}" style="height: 60px; padding: 0; margin: 0">
                        </div>
                        <div class="col-xs-9 text-left">
                            <div style="height: 60px;">
                                <p style="line-height: initial; height: 60px; display: table-cell; vertical-align: middle;">
                                    {{ $search_result->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="px-3 py-3">No results found</div>
            @endforelse
        </div>
    @elseif( $movie || $series || $person)
        <div style="width: 100%; min-height: 60px; margin: 0; padding: 3px; display: block; background-color: #2b2b2b;"
             @mouseenter="lock = true;" @mouseleave="lock = false">
            <div class="px-3 my-3">Keep typing to get results</div>
        </div>
    @endif
</div>






















