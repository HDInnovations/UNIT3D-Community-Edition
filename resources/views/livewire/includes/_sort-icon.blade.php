@if($sortField === $field)
    <i class="{{ config('other.font-awesome') }} fa-sort-amount-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
@else
    <i class="{{ config('other.font-awesome') }} fa-sort-alt" style="opacity: .35;"></i>
@endif