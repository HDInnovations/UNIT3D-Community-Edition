@extends('layout.default')

@section('breadcrumb')
	<li>
		<a href="{{ route('playlists.index') }}" itemprop="url" class="l-breadcrumb-item-link">
			<span itemprop="title" class="l-breadcrumb-item-link-title">Playlists</span>
		</a>
	</li>
	<li>
		<a href="{{ route('playlists.show', ['id' => $playlist->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
			<span itemprop="title" class="l-breadcrumb-item-link-title">{{ $playlist->name }}</span>
		</a>
	</li>
@endsection

@section('content')
	<div class="container">
		<div class="block">
			<section class="inner_content header" style="background-image: url({{ $meta->backdrop ?? 'https://via.placeholder.com/1400x800' }});">
				<div class="bg_filter">
					<div class="single_column">
						<h2>{{ $playlist->name }}</h2>

						<ul class="list_menu_bar">
							<li class="account">
								<a href="#!">
									@if ($playlist->user->image != null)
										<img src="{{ url('files/img/' . $playlist->user->image) }}" alt="{{ $playlist->user->username }}" style=" width: 50px">
									@else
										<img src="{{ url('img/profile.png') }}" alt="{{ $playlist->user->username }}" style=" width: 50px">
									@endif
								</a>
								<p>A list by<br><a href="#!">{{ $playlist->user->username }}</a></p>
							</li>
						</ul>

						<h3 class="text-bold">About this list</h3>
						<div class="description">
							<p>{{ $playlist->description }}</p>
							<br>
							@if(auth()->user()->id == $playlist->user_id || auth()->user()->group->is_modo)
								<button data-toggle="modal" data-target="#modal_playlist_torrent" class="btn btn-md btn-success">
									<i class="{{ config('other.font-awesome') }} fa-search-plus"></i> Add Torrent
								</button>
								<form action="{{ route('playlists.destroy', ['id' => $playlist->id]) }}" method="POST" style="display: inline;">
									@csrf
									@method('DELETE')
									<a href="{{ route('playlists.edit', ['id' => $playlist->id]) }}" class="btn btn-warning">
										<i class="{{ config('other.font-awesome') }} fa-edit"></i> Edit Playlist
									</a>
									<button type="submit" class="btn btn-danger">
										<i class="{{ config('other.font-awesome') }} fa-trash"></i> Delete Playlist
									</button>
								</form>
							@endif
						</div>
					</div>
				</div>
			</section>
		</div>

		<div class="block">
			<div class="row">
				@php $meta = null; @endphp
				@php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb')); @endphp
				@foreach($torrents as $t)
					@if ($t->category->tv_meta)
						@if ($t->tmdb || $t->tmdb != 0)
							@php $meta = $client->scrape('tv', null, $t->tmdb); @endphp
						@else
							@php $meta = $client->scrape('tv', 'tt'. $t->imdb); @endphp
						@endif
					@endif
					@if ($t->category->movie_meta)
						@if ($t->tmdb || $t->tmdb != 0)
							@php $meta = $client->scrape('movie', null, $t->tmdb); @endphp
						@else
							@php $meta = $client->scrape('movie', 'tt'. $t->imdb); @endphp
						@endif
					@endif
					<div class="col-md-6">
						<div class="card is-torrent">
							<div class="card_head">
								@if ($t->tmdb != 0)
									<a href="{{ route('torrents.similar', ['category_id' => $t->category_id, 'tmdb' => $t->tmdb]) }}" role="button"
									   data-toggle="tooltip" data-placement="top" data-original-title="@lang('torrent.similar')" class="btn btn-xs btn-primary" style="float: left; margin-right: 10px;">
										<i class='{{ config("other.font-awesome") }} fa-copy'></i>
									</a>
								@endif
								&nbsp;
								@if (config('torrent.download_check_page') == 1)
									<a href="{{ route('download_check', ['slug' => $t->slug, 'id' => $t->id]) }}" role="button"
									   data-toggle="tooltip" data-placement="top" data-original-title="@lang('common.download')" class="btn btn-xs btn-success" style="float: left; margin-right: 10px;">
										<i class='{{ config("other.font-awesome") }} fa-download'></i>
									</a>
								@else
									<a href="{{ route('download', ['slug' => $t->slug, 'id' => $t->id]) }}" role="button"
									   data-toggle="tooltip" data-placement="top" data-original-title="@lang('common.download')" class="btn btn-xs btn-success" style="float: left; margin-right: 10px;">
										<i class='{{ config("other.font-awesome") }} fa-download'></i>
									</a>
								@endif
								&nbsp;
								@if(auth()->user()->id == $playlist->user_id || auth()->user()->group->is_modo)
									<form action="{{ route('playlists.detach', ['id' => $t->id]) }}" method="POST" style="float: left; margin-right: 10px;"
									      data-toggle="tooltip" data-placement="top" data-original-title="@lang('common.delete')">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-xs btn-danger">
											<i class="{{ config('other.font-awesome') }} fa-trash"></i>
										</button>
									</form>
								@endif

								<span class="badge-user text-bold" style="float:right;">
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up text-green"></i> {{ $t->seeders }} /
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-down text-red"></i> {{ $t->leechers }} /
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-check text-orange"></i>{{ $t->times_completed }}
                                </span>&nbsp;
								<span class="badge-user text-bold text-blue" style="float:right;">{{ $t->getSize() }}</span>&nbsp;
								<span class="badge-user text-bold text-blue" style="float:right;">{{ $t->type }}</span>&nbsp;
								<span class="badge-user text-bold text-blue" style="float:right;">{{ $t->category->name }}</span>&nbsp;
							</div>
							<div class="card_body">
								<div class="body_poster">
									@if ($t->category->movie_meta || $t->category->tv_meta && isset($meta) && $meta->poster && $meta->title)
										<img src="{{ $meta->poster ?? 'https://via.placeholder.com/600x900' }}" class="show-poster"
										     data-name='<i style="color: #a5a5a5;">{{ $t->meta->title ?? 'N/A' }}</i>' data-image='<img src="{{ $meta->poster ?? 'https://via.placeholder.com/600x900' }}" alt="@lang('torrent.poster')" style="height: 1000px;">'
										     class="torrent-poster-img-small show-poster" alt="@lang('torrent.poster')">
									@endif

									@if ($t->category->game_meta && isset($t->meta) && $meta->cover->image_id && $meta->name)
										<img src="https://images.igdb.com/igdb/image/upload/t_original/{{ $t->meta->cover->image_id }}.jpg" class="show-poster"
										     data-name='<i style="color: #a5a5a5;">{{ $meta->name ?? 'N/A' }}</i>' data-image='<img src="https://images.igdb.com/igdb/image/upload/t_original/{{ $meta->cover->image_id }}.jpg" alt="@lang('torrent.poster')" style="height: 1000px;">'
										     class="torrent-poster-img-small show-poster" alt="@lang('torrent.poster')">
									@endif

									@if ($t->category->no_meta || $t->category->music_meta || ! $meta)
										<img src="https://via.placeholder.com/600x900" class="show-poster"
										     data-name='<i style="color: #a5a5a5;">N/A</i>' data-image='<img src="https://via.placeholder.com/600x900" alt="@lang('torrent.poster')" style="height: 1000px;">'
										     class="torrent-poster-img-small show-poster" alt="@lang('torrent.poster')">
									@endif
								</div>
								<div class="body_description">
									<h3 class="description_title">
										<a href="{{ route('torrent', ['slug' => $t->slug, 'id' => $t->id]) }}">{{ $t->name }}
											@if($t->category->movie_meta || $t->category->tv_meta && isset($t->meta) && $meta->releaseYear)
												<span class="text-bold text-pink"> {{ $meta->releaseYear }}</span>
											@endif
											@if($t->category->game_meta && isset($meta) && $meta->first_release_date)
												<span class="text-bold text-pink"> {{ date('Y', strtotime( $meta->first_release_date)) }}</span>
											@endif
										</a>
									</h3>
									@if ($t->category->movie_meta && isset($meta) && $meta->genres)
										@foreach ($meta->genres as $genre)
											<span class="genre-label">{{ $genre }}</span>
										@endforeach
									@endif
									@if ($t->category->tv_meta && isset($meta) && $meta->genres)
										@foreach ($meta->genres as $genre)
											<span class="genre-label">{{ $genre }}</span>
										@endforeach
									@endif
									@if ($t->category->game_meta && isset($meta) && $meta->genres)
										@foreach ($meta->genres as $genre)
											<span class="genre-label">{{ $genre->name }}</span>
										@endforeach
									@endif
									<p class="description_plot">
										@if($t->category->movie_meta || $t->category->tv_meta && $meta && $meta->plot)
											{{ $meta->plot }}
										@endif
									</p>
								</div>
							</div>
							<div class="card_footer">
								<div style="float: left;">
									@if ($t->anon == 1)
										<span class="badge-user text-orange text-bold">{{ strtoupper(trans('common.anonymous')) }}
											@if (auth()->user()->id == $t->user->id || auth()->user()->group->is_modo)
												<a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">
                                                            ({{ $t->user->username }})
                                                        </a>
											@endif
                                                </span>
									@else
										<a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">
                                            <span class="badge-user text-bold" style="color:{{ $t->user->group->color }}; background-image:{{ $t->user->group->effect }};">
                                                <i class="{{ $t->user->group->icon }}" data-toggle="tooltip" title=""
                                                   data-original-title="{{ $t->user->group->name }}"></i> {{ $t->user->username }}
                                            </span>
										</a>
									@endif
								</div>
								<span class="badge-user text-bold" style="float: right;">
                                            <i class="{{ config('other.font-awesome') }} fa-thumbs-up text-gold"></i>
                                            @if($meta && ($meta->imdbRating || $meta->tmdbVotes))
										@if (auth()->user()->ratings == 1)
											{{ $meta->imdbRating }}/10 ({{ $meta->imdbVotes }} @lang('torrent.votes'))
										@else
											{{ $meta->tmdbRating }}/10 ({{ $meta->tmdbVotes }} @lang('torrent.votes'))
										@endif
									@endif
                                        </span>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>

		{{--<div class="block">
				<div class="row">
					<div class="col-xs-6 col-sm-4 col-md-3">
						<div class="image-box text-center mb-20">
							<div class="overlay-container">
								<img class='details-poster' src="{{ $meta->poster }}">
								<div class="overlay-top">
									@if ($t->tmdb != 0)
										<a href="{{ route('torrents.similar', ['category_id' => $t->category_id, 'tmdb' => $t->tmdb]) }}" role="button"
										   data-toggle="tooltip" data-placement="right" data-original-title="@lang('torrent.similar')" class="btn btn-xs btn-primary" style="float: left;">
											<i class='{{ config("other.font-awesome") }} fa-copy'></i>
										</a>
									@endif

                                    @if (config('torrent.download_check_page') == 1)
                                        <a href="{{ route('download_check', ['slug' => $t->slug, 'id' => $t->id]) }}" role="button"
                                           data-toggle="tooltip" data-placement="right" data-original-title="@lang('common.download')" class="btn btn-xs btn-success" style="float: left;">
                                            <i class='{{ config("other.font-awesome") }} fa-download'></i>
                                        </a>
                                    @else
                                        <a href="{{ route('download', ['slug' => $t->slug, 'id' => $t->id]) }}" role="button"
                                           data-toggle="tooltip" data-placement="right" data-original-title="@lang('common.download')" class="btn btn-xs btn-success" style="float: left;">
                                            <i class='{{ config("other.font-awesome") }} fa-download'></i>
                                        </a>
                                    @endif

									@if(auth()->user()->id == $playlist->user_id || auth()->user()->group->is_modo)
										<form action="{{ route('playlists.detach', ['id' => $t->id]) }}" method="POST" style="float: right;"
										      data-toggle="tooltip" data-placement="left" data-original-title="@lang('common.delete')">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-xs btn-danger">
												<i class="{{ config('other.font-awesome') }} fa-trash"></i>
											</button>
										</form>
									@endif
									<div class="text">
										<h3 style="font-size: 25px;">
											<a data-id="{{ $t->id }}" data-slug="{{ $t->slug }}" href="{{ route('torrent', ['slug' => $t->slug, 'id' => $t->id]) }}">{{ $t->name }}</a>
										</h3>
									</div>
								</div>
								<div class="overlay-bottom">
									<div class="links">
										<span class='label label-success'>{{ $t->type }}</span>
										<div class="separator mt-10"></div>
										<ul class="list-unstyled margin-clear">
											<li><i class="{{ config('other.font-awesome') }} fa-arrow-up"></i> {{ trans('torrent.seeders') }}: {{ $t->seeders }}</li>
											<li><i class="{{ config('other.font-awesome') }} fa-arrow-down"></i> {{ trans('torrent.leechers') }}: {{ $t->leechers }}</li>
											<li><i class="{{ config('other.font-awesome') }} fa-check"></i> {{ trans('torrent.completed') }}: {{ $t->times_completed }}</li>
											<li><i class="{{ config('other.font-awesome') }} fa-server"></i> {{ trans('torrent.size') }}: {{ $t->getSize() }}</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>--}}

		<div class="block" id="comments">
			<div class="row ">
				<div class="col-md-12 col-sm-12">
					<div class="panel panel-chat shoutbox">
						<div class="panel-heading">
							<h4>
								<i class="{{ config('other.font-awesome') }} fa-comment"></i> @lang('common.comments')
							</h4>
						</div>
						<div class="panel-body no-padding">
							<ul class="media-list comments-list">
								@if (count($playlist->comments) == 0)
									<div class="text-center"><h4 class="text-bold text-danger">
											<i class="{{ config('other.font-awesome') }} fa-frown"></i> @lang('common.no-comments')!</h4>
									</div>
								@else
									@foreach ($playlist->comments as $comment)
										<li class="media" style="border-left: 5px solid #01BC8C">
											<div class="media-body">
												@if ($comment->anon == 1)
													<a href="#" class="pull-left" style="padding-right: 10px">
														<img src="{{ url('img/profile.png') }}"
														     alt="{{ $comment->user->username }}" class="img-avatar-48">
														<strong>{{ strtoupper(trans('common.anonymous')) }}</strong></a> @if (auth()->user()->id == $comment->user->id || auth()->user()->group->is_modo)
														<a href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}" style="color:{{ $comment->user->group->color }}">(<span><i class="{{ $comment->user->group->icon }}"></i> {{ $comment->user->username }}</span>)</a> @endif
												@else
													<a href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}"
													   class="pull-left" style="padding-right: 10px">
														@if ($comment->user->image != null)
															<img src="{{ url('files/img/' . $comment->user->image) }}"
															     alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
													@else
														<img src="{{ url('img/profile.png') }}"
														     alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
													@endif
													<strong><a
																href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}" style="color:{{ $comment->user->group->color }}"><span><i class="{{ $comment->user->group->icon }}"></i> {{ $comment->user->username }}</span></a></strong> @endif
												<span class="text-muted"><small><em>{{ $comment->created_at->toDayDateTimeString() }} ({{ $comment->created_at->diffForHumans() }})</em></small></span>
												@if ($comment->user_id == auth()->id() || auth()->user()->group->is_modo)
													<a title="@lang('common.delete-comment')"
													   href="{{route('comment_delete',['comment_id'=>$comment->id])}}"><i
																class="pull-right {{ config('other.font-awesome') }} fa fa-times" aria-hidden="true"></i></a>
													<a title="@lang('common.edit-comment')" data-toggle="modal"
													   data-target="#modal-comment-edit-{{ $comment->id }}"><i
																class="pull-right {{ config('other.font-awesome') }} fa-pencil"
																aria-hidden="true"></i></a>
												@endif
												<div class="pt-5">
													@emojione($comment->getContentHtml())
												</div>
											</div>
										</li>
										@include('partials.modals', ['comment' => $comment])
									@endforeach
								@endif
							</ul>
						</div>
					</div>
				</div>
				<!-- /Comments -->
				<br>
				<!-- Add comment -->
				<div class="col-md-12">
					<form role="form" method="POST" action="{{ route('comment_playlist', ['id' => $playlist->id]) }}">
						@csrf
						<div class="form-group">
							<label for="content">@lang('common.your-comment'):</label><span class="badge-extra">@lang('common.type')
                        <strong>:</strong> @lang('common.for') emoji</span> <span
									class="badge-extra">BBCode @lang('common.is-allowed')</span>
							<textarea id="content" name="content" cols="30" rows="5" class="form-control"></textarea>
						</div>
						<button type="submit" class="btn btn-danger">@lang('common.submit')</button>
						<label class="radio-inline"><strong>@lang('common.anonymous') @lang('common.comment')
								:</strong></label>
						<input type="radio" value="1" name="anonymous"> @lang('common.yes')
						<input type="radio" value="0" checked="checked" name="anonymous"> @lang('common.no')
					</form>
				</div>
				<!-- /Add comment -->
			</div>
		</div>
	</div>

	{{-- Add Torrent Modal --}}
	<div class="modal fade" id="modal_playlist_torrent" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="container-fluid">
					<form role="form" method="POST" action="{{ route('playlists.attach') }}">
						@csrf
						<input id="playlist_id" name="playlist_id" type="hidden" value="{{ $playlist->id }}">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title" id="myModalLabel">Add Torrent To Playlist</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="report_reason">Torrent ID</label>
							</div>
							<div class="form-group">
								<label>
									<input type="text" name="torrent_id" class="form-control">
								</label>
							</div>
							<div class="form-group">
								<input class="btn btn-primary" type="submit" value="Save">
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascripts')
	<script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(document).ready(function () {
        $('#content').wysibb({});
        emoji.textcomplete()
      })
	</script>
@endsection