<div class="button-block">
	@if (file_exists(public_path().'/files/torrents/'.$torrent->file_name))
		@if (config('torrent.download_check_page') == 1)
			<a href="{{ route('download_check', ['id' => $torrent->id]) }}" role="button" class="down btn btn-sm btn-success">
				<i class='{{ config("other.font-awesome") }} fa-download'></i> @lang('common.download')
			</a>
		@else
			<a href="{{ route('download', ['id' => $torrent->id]) }}" role="button" class="down btn btn-sm btn-success">
				<i class='{{ config("other.font-awesome") }} fa-download'></i> @lang('common.download')
			</a>

			@if ($torrent->free == "0" && config('other.freeleech') == false && !$personal_freeleech && $user->group->is_freeleech == 0 && !$freeleech_token)
				<form action="{{ route('freeleech_token', ['id' => $torrent->id]) }}" method="POST" style="display: inline;">
					@csrf
					<button type="submit" class="btn btn-primary btn-sm torrent-freeleech-token"
					        data-toggle=tooltip
					        data-html="true"
					        title='{!! trans('torrent.fl-tokens-left', ['tokens' => $user->fl_tokens]) !!}!'>
						@lang('torrent.use-fl-token')
					</button>
				</form>
			@endif
		@endif
	@else
		<a href="magnet:?dn={{ $torrent->name }}&xt=urn:btih:{{ $torrent->info_hash }}&as={{ route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => $user->rsskey ]) }}&tr={{ route('announce', ['passkey' => $user->passkey]) }}&xl={{ $torrent->size }}" role="button" class="down btn btn-sm btn-success">
			<i class='{{ config("other.font-awesome") }} fa-magnet'></i> @lang('common.magnet')
		</a>
	@endif

	@livewire('thank-button', ['torrent' => $torrent->id])

	@if ($torrent->nfo != null)
		<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-10">
			<i class='{{ config("other.font-awesome") }} fa-info-circle'></i> NFO
		</button>
	@endif

	<form action="{{ route('comment_thanks', ['id' => $torrent->id]) }}" method="POST" style="display: inline;">
		@csrf
		<button type="submit" class="btn btn-sm btn-primary">
			<i class='{{ config("other.font-awesome") }} fa-heart'></i> @lang('torrent.quick-comment')
		</button>
	</form>

	<a data-toggle="modal" href="#myModal" role="button" class="btn btn-sm btn-primary">
		<i class='{{ config("other.font-awesome") }} fa-file'></i>  @lang('torrent.show-files')
	</a>

	@livewire('bookmark-button', ['torrent' => $torrent->id])

	@if ($playlists->count() > 0)
		<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_playlist_torrent">
			<i class="{{ config('other.font-awesome') }} fa-list-ol"></i> @lang('torrent.add-to-playlist')
		</button>
	@endif

	@if ($current = $user->history->where('info_hash', $torrent->info_hash)->first())
		@if ($current->seeder == 0 && $current->active == 1 && $torrent->seeders <= 2)
			<form action="{{ route('reseed', ['id' => $torrent->id]) }}" method="POST" style="display: inline;">
				@csrf
				<button type="submit" class="btn btn-sm btn-primary">
					<i class='{{ config("other.font-awesome") }} fa-envelope'></i> @lang('torrent.request-reseed')
				</button>
			</form>
		@endif
	@endif

	<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal_torrent_report">
		<i class="{{ config('other.font-awesome') }} fa-fw fa-eye"></i> @lang('common.report')
	</button>

	<a role="button" class="btn btn-sm btn-primary" href="{{ route('upload_form', ['category_id' => $torrent->category_id, 'title' => \rawurlencode($torrent->name) ?? 'Unknown', 'imdb' => $torrent->imdb, 'tmdb' => $torrent->tmdb]) }}">
		<i class="{{ config('other.font-awesome') }} fa-upload"></i> @lang('common.upload')
	</a>
</div>