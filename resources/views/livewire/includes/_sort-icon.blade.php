@if ($sortField === $field)
    <i
        class="{{ config('other.font-awesome') }} fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"
    ></i>
@else
    <i class="{{ config('other.font-awesome') }} fa-sort" style="opacity: 0.35"></i>
@endif
