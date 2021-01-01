@if($sortField === $field)
	<i class="fas fa-sort-amount-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
@else
	<i class="fas fa-sort-amount-up" style="opacity: .35;"></i>
@endif