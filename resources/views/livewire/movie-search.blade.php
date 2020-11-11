<div>
	<div>
		<div class="mb-10">
			<input type="text" wire:model="searchTerm" class="form-control" placeholder="Search By Name"/>
		</div>

		@foreach($movies as $movie)
			<div class="col-md-12">
				<div class="card is-torrent" style=" height: 265px;">
					<div class="card_head">
						<span class="badge-user text-bold" style="float:right;">
							<a @if($movie->torrents()->first() !== null) href="{{ route('torrents.similar', ['category_id' => $movie->torrents()->first()->category_id, 'tmdb' => $movie->id]) }}" @else href="#" @endif>
								{{ $movie->torrents_count }} Torrents Matched
							</a>
						</span>
						@if ($movie->companies)
							@foreach ($movie->companies as $company)
								<span class="badge-user text-bold" style="float:right;">
									{{ $company->name }}
								</span>
							@endforeach
						@endif
					</div>
					<div class="card_body">
						<div class="body_poster">
							@if($movie->poster)
								<img src="{{ $movie->poster }}" class="show-poster">
							@endif
						</div>
						<div class="body_description">
							<h3 class="description_title">
								<a href="{{ route('mediahub.movies.show', ['id' => $movie->id]) }}">{{ $movie->title }}
									@if($movie->release_date)
										<span class="text-bold text-pink"> {{ $movie->release_date }}</span>
									@endif
								</a>
							</h3>
							@if ($movie->genres)
								@foreach ($movie->genres as $genre)
									<span class="genre-label">{{ $genre->name }}</span>
								@endforeach
							@endif
							<p class="description_plot">
								{{ $movie->overview }}
							</p>
						</div>
					</div>
				</div>
			</div>
		@endforeach
		<br>
		<div class="text-center">
			{{ $movies->links() }}
		</div>
	</div>
</div>
