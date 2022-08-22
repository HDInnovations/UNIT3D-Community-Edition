<section class="panelV2">
	<h1 class="panel__heading">Missing Media</h1>
	<table class="data-table" id="missing-media-table">
		<thead>
		<tr>
			<th wire:click="sortBy('title')" :direction="$sortField === 'title' ? $sortDirection : null" role="columnheader button">
				{{ __('common.name') }}
				@include('livewire.includes._sort-icon', ['field' => 'title'])
			</th>
			<th wire:click="sortBy('requests_count')" :direction="$sortField === 'requests_count' ? $sortDirection : null" role="columnheader button">
				{{ __('request.requests') }}
				@include('livewire.includes._sort-icon', ['field' => 'requests_count'])
			</th>
			@foreach($types as $type)
				<th>{{ $type->name }}</th>
			@endforeach
		</tr>
		</thead>
		<tbody>
		@foreach($medias as $media)
			<tr>
				<td>
					<a href="{{ route('torrents.similar', ['category_id' => 1, 'tmdb' => $media->id]) }}">
						{{ $media->title }} ({{ \substr($media->release_date, 0, 4) ?? '' }})
					</a>
				</td>
				<td>
					<a href="{{ route('requests.index', ['categories' => [1], 'tmdbId' => $media->id, 'unfilled' => 1]) }}">
						{{ $media->requests_count }}
					</a>
				</td>
				@foreach($types as $type)
					@if($media->torrents->where('type_id', '=', $type->id)->isEmpty())
						<td style="color: #f05555 !important; background: rgba(107, 6, 6, 0.58) !important; font-weight: bold">
							Missing
						</td>
					@else
						<td style="color: #55b160 !important; background: rgba(1, 70, 10, 0.53) !important; font-weight: bold;">
							{{ $media->torrents->where('type_id', '=', $type->id)->implode('resolution.name', ' | ') }}
						</td>
					@endif
				@endforeach
			</tr>
		@endforeach
		</tbody>
	</table>
	{{ $medias->links('partials.pagination') }}
</section>
