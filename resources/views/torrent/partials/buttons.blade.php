<div class="button-block">
    @if (file_exists(public_path().'/files/torrents/'.$torrent->file_name))
        @if (config('torrent.download_check_page') == 1)
            <a href="{{ route('download_check', ['id' => $torrent->id]) }}" role="button"
               class="down btn btn-sm btn-success">
                <i class='{{ config("other.font-awesome") }} fa-download'></i> {{ __('common.download') }}
            </a>
        @else
            <a href="{{ route('download', ['id' => $torrent->id]) }}" role="button" class="down btn btn-sm btn-success">
                <i class='{{ config("other.font-awesome") }} fa-download'></i> {{ __('common.download') }}
            </a>

            @if ($torrent->free == "0" && config('other.freeleech') == false && !$personal_freeleech && $user->group->is_freeleech == 0 && !$freeleech_token)
                <form action="{{ route('freeleech_token', ['id' => $torrent->id]) }}" method="POST"
                      style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm torrent-freeleech-token"
                            data-toggle=tooltip
                            data-html="true"
                            title='{!! __('torrent.fl-tokens-left', ['tokens' => $user->fl_tokens]) !!}!'>
                        {{ __('torrent.use-fl-token') }}
                    </button>
                </form>
            @endif
        @endif
    @else
        <a href="magnet:?dn={{ $torrent->name }}&xt=urn:btih:{{ $torrent->info_hash }}&as={{ route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => $user->rsskey ]) }}&tr={{ route('announce', ['passkey' => $user->passkey]) }}&xl={{ $torrent->size }}"
           role="button" class="down btn btn-sm btn-success">
            <i class='{{ config("other.font-awesome") }} fa-magnet'></i> {{ __('common.magnet') }}
        </a>
    @endif

    @livewire('thank-button', ['torrent' => $torrent->id])

    @if ($torrent->nfo != null)
        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-10">
            <i class='{{ config("other.font-awesome") }} fa-info-circle'></i> NFO
        </button>
    @endif

    <a data-toggle="modal" href="#myModal" role="button" class="btn btn-sm btn-primary">
        <i class='{{ config("other.font-awesome") }} fa-file'></i> {{ __('torrent.show-files') }}
    </a>

    @livewire('bookmark-button', ['torrent' => $torrent->id])

    @if ($playlists->count() > 0)
        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_playlist_torrent">
            <i class="{{ config('other.font-awesome') }} fa-list-ol"></i> {{ __('torrent.add-to-playlist') }}
        </button>
    @endif

    @if ($current = $user->history->where('torrent_id', $torrent->id)->first())
        @if ($current->seeder == 0 && $current->active == 1 && $torrent->seeders <= 2)
            <form action="{{ route('reseed', ['id' => $torrent->id]) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class='{{ config("other.font-awesome") }} fa-envelope'></i> {{ __('torrent.request-reseed') }}
                </button>
            </form>
        @endif
    @endif

    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal_torrent_report">
        <i class="{{ config('other.font-awesome') }} fa-fw fa-eye"></i> {{ __('common.report') }}
    </button>

    <a role="button" class="btn btn-sm btn-primary"
       href="{{ route('upload_form', ['category_id' => $torrent->category_id, 'title' => rawurlencode($torrent->name) ?? 'Unknown', 'imdb' => $torrent->imdb, 'tmdb' => $torrent->tmdb]) }}">
        <i class="{{ config('other.font-awesome') }} fa-upload"></i> {{ __('common.upload') }}
    </a>
</div>