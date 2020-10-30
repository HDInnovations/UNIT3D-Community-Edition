<div>
	<div class="mb-10">
		<input type="text" wire:model="searchTerm" class="form-control" placeholder="Search By Name"/>
	</div>

	@foreach($shows as $show)
		<div class="col-md-12">
			<div class="card is-torrent" style=" height: 265px;">
				<div class="card_head">
					<span class="badge-user text-bold" style="float:right;">
						<a href="{{ route('torrents.similar', ['category_id' => $show->torrents->first()->category_id, 'tmdb' => $show->id]) }}" >
							{{ $show->torrents_count }} Torrents Matched
						</a>
					</span>
					<span class="badge-user text-bold" style="float:right;">
						{{ $show->seasons_count }} Seasons
					</span>
					@if ($show->networks)
						@foreach ($show->networks as $network)
							<span class="badge-user text-bold" style="float:right;">
								{{ $network->name }}
							</span>
						@endforeach
					@endif
				</div>
				<div class="card_body">
					<div class="body_poster">
						@if($show->poster)
							<img src="{{ $show->poster }}" class="show-poster">
						@endif
					</div>
					<div class="body_description">
						<h3 class="description_title">
							<a href="{{ route('mediahub.shows.show', ['id' => $show->id]) }}">{{ $show->name }}
								@if($show->first_aired)
									<span class="text-bold text-pink"> {{ $show->first_aired }}</span>
								@endif
							</a>
						</h3>
						@if ($show->genres)
							@foreach ($show->genres as $genre)
								<span class="genre-label">{{ $genre->name }}</span>
							@endforeach
						@endif
						<p class="description_plot">
							{{ $show->overview }}
						</p>
					</div>
				</div>
			</div>
		</div>
	@endforeach
	<br>
	<div class="text-center">
		{{ $shows->links() }}
	</div>
</div>
