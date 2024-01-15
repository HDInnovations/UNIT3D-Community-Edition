<td class="torrent-search--grouped__overview">
    <div>
        @if (auth()->user()->group->is_editor || auth()->user()->group->is_modo || auth()->id() === $torrent->user_id)
            <a
                href="{{ route('torrents.edit', ['id' => $torrent->id]) }}"
                title="{{ __('common.edit') }}"
                class="torrent-search--grouped__edit"
            >
                <i class="{{ config('other.font-awesome') }} fa-pencil-alt"></i>
            </a>
        @endif

        <h3 class="torrent-search--grouped__name">
            <a href="{{ route('torrents.show', ['id' => $torrent->id]) }}">
                @switch($media->meta)
                    @case('movie')
                        {{ \preg_replace('/^.*( ' . (substr($media->release_date ?? '0', 0, 4) - 1) . ' | ' . substr($media->release_date ?? '0', 0, 4) . ' | ' . (substr($media->release_date ?? '0', 0, 4) + 1) . ' )/i', '', $torrent->name) }}

                        @break
                    @case('tv')
                        {{-- Removes the following patterns from the name: S01, S01E01, S01E01E02, S01E01E02E03, S01E01-E03, 2000-01-01, 2000-01-01 --}}
                        {{ \preg_replace('/^.*( S\d{2,4}(?:-?E\d{2,4})*? | \d{4}(?:-\d{2}){1,2} | ' . (substr($media->first_air_date ?? '0', 0, 4) - 1) . ' | ' . substr($media->first_air_date ?? '0', 0, 4) . ' | ' . (substr($media->first_air_date ?? '0', 0, 4) + 1) . ' )/i', '', $torrent->name) }}

                        @break
                @endswitch
            </a>
        </h3>
        @include('components.partials._torrent-icons')
    </div>
</td>
<td class="torrent-search--grouped__download">
    @if (config('torrent.download_check_page') == 1)
        <a
            href="{{ route('download_check', ['id' => $torrent->id]) }}"
            title="{{ __('common.download') }}"
        >
            <i class="{{ config('other.font-awesome') }} fa-arrow-alt-to-bottom"></i>
        </a>
    @else
        <a
            href="{{ route('download', ['id' => $torrent->id]) }}"
            title="{{ __('common.download') }}"
        >
            <i class="{{ config('other.font-awesome') }} fa-arrow-alt-to-bottom"></i>
        </a>
    @endif
    @if (config('torrent.magnet') == 1)
        <a
            href="magnet:?dn={{ $torrent->name }}&xt=urn:btih:{{ bin2hex($torrent->info_hash) }}&as={{ route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => auth()->user()->rsskey]) }}&tr={{ route('announce', ['passkey' => $user->passkey]) }}&xl={{ $torrent->size }}"
            title="{{ __('common.magnet') }}"
        >
            <i class="{{ config('other.font-awesome') }} fa-magnet"></i>
        </a>
    @endif
</td>
<td class="torrent-search--grouped__size">
    <span title="{{ $torrent->size }} B">
        {{ $torrent->getSize() }}
    </span>
</td>
<td class="torrent-search--grouped__seeders">
    <a class="text-green" href="{{ route('peers', ['id' => $torrent->id]) }}">
        {{ $torrent->seeders }}
    </a>
</td>
<td class="torrent-search--grouped__leechers">
    <a class="text-red" href="{{ route('peers', ['id' => $torrent->id]) }}">
        {{ $torrent->leechers }}
    </a>
</td>
<td class="torrent-search--grouped__completed">
    <a class="text-orange" href="{{ route('history', ['id' => $torrent->id]) }}">
        {{ $torrent->times_completed }}
    </a>
</td>
<td class="torrent-search--grouped__age">
    <time datetime="{{ $torrent->created_at }}" title="{{ $torrent->created_at }}">
        {{ $torrent->created_at->diffForHumans() }}
    </time>
</td>
