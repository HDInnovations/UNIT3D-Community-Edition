<div>
	<table class="table table-condensed table-bordered" id="missing-media-table">
		<thead>
		<tr>
			<th>
				<div sortable wire:click="sortBy('title')" :direction="$sortField === 'title' ? $sortDirection : null" role="button">
					{{ __('common.name') }}
					@include('livewire.includes._sort-icon', ['field' => 'title'])
				</div>
			</th>
			<th>Requests</th>
			@foreach(App\Models\Type::all()->sortBy('position') as $type)
				<th>{{ $type->name }}</th>
			@endforeach
		</tr>
		</thead>
		<tbody>
		@foreach($medias as $media)
		<tr>
			<td>
				<a href="{{ route('torrents.similar', ['category_id' => 1, 'tmdb' => $media->id]) }}">{{ $media->title }} ({{ \substr($media->release_date, 0, 4) ?? '' }})</a>
			</td>
			<td>
				<a href="{{ url('requests?categories[]=1&tmdbId='.$media->id.'&unfilled=1') }}">{{ App\Models\TorrentRequest::where('category_id', '=', 1)->whereNull('filled_hash')->whereNull('claimed')->where('tmdb', '=', $media->id)->count() }}</a>
			</td>
			@foreach(App\Models\Type::all()->sortBy('position') as $type)
				@if($media->torrents->where('type_id', '=', $type->id)->count() > 0)
					<td style="color: #55b160 !important; background: rgba(1, 70, 10, 0.53) !important;">
						@php $res = $media->torrents->where('type_id', '=', $type->id)->pluck('resolution_id'); @endphp
						<span class="text-bold">
							@foreach(App\Models\Resolution::whereIn('id', $res)->get()->sortBy('position') as $resolution) {{ $resolution->name }}
								@if(! $loop->last)
									|
								@endif
							@endforeach
						</span>
					</td>
				@else
					<td style="color: #f05555 !important; background: rgba(107, 6, 6, 0.58) !important;"><span class="text-bold text-center">Missing</span></td>
				@endif
			@endforeach
		</tr>
		@endforeach
		</tbody>
	</table>
	<br>
	<div class="text-center">
		{{ $medias->links() }}
	</div>
</div>
