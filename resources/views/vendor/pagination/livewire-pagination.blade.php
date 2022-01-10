@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li>
                    <a wire:click="previousPage" rel="prev" aria-label="{{ __('pagination.previous') }}">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a wire:click="gotoPage({{ $page }})">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a wire:click="nextPage" rel="next" aria-label="{{ __('pagination.next') }}">&rsaquo;</a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
        <br>
        <span class="text-bold text-muted badge-extra">
			{!! __('Showing') !!}
			<span class="text-bright">{{ $paginator->firstItem() }}</span>
			{!! __('to') !!}
			<span class="text-bright">{{ $paginator->lastItem() }}</span>
			{!! __('of') !!}
			<span class="text-bright">{{ $paginator->total() }}</span>
			{!! __('results') !!}
		</span>
    </nav>
@endif