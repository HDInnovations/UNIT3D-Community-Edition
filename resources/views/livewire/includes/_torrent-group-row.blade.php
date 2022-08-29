<td class="torrent-search--grouped__overview">
    <div>
        @if(auth()->user()->group->is_modo || auth()->user()->id === $torrent->user_id)
            <a
                    href="{{ route('edit_form', ['id' => $torrent->id]) }}"
                    title="{{ __('common.edit') }}"
                    class="torrent-search--grouped__edit"
            >
                <i class="{{ config('other.font-awesome') }} fa-pencil-alt"></i>
            </a>
        @endif
        <h3 class="torrent-search--grouped__name">
            <a href="{{ route('torrent', ['id' => $torrent->id]) }}">
                @switch ($mediaType)
                    @case('movie')
                    {{ \preg_replace('/^.*( '.(substr($meta->release_date ?? '0', 0, 4) - 1).' | '.substr($meta->release_date ?? '0', 0, 4).' | '.(substr($meta->release_date ?? '0', 0, 4) + 1).' )/i', '', $torrent->name) }}
                    @break
                    @case('tv')
                    {{ \preg_replace('/^.*( S\d{2,4} | S\d{2,4}(?:E\d{2,4})*? |  S\d{2,4}E\d{2,4}-E\d{2,4} | \d{4}-\d{2}-\d{2} | \d{4}-\d{2} | '.(substr($meta->first_air_date ?? '0', 0, 4) - 1).' | '.substr($meta->first_air_date ?? '0', 0, 4).' | '.(substr($meta->first_air_date ?? '0', 0, 4) + 1).' )/i', '', $torrent->name) }}
                    @break
                @endswitch
            </a>
        </h3>
        <span class="torrent-search--grouped__flags">
                @if ($torrent->internal == 1)
                <i
                        class='{{ config('other.font-awesome') }} fa-magic torrent-flag__internal'
                        style="color: #baaf92;"
                        title='{{ __('torrent.internal-release') }}'
                ></i>
            @endif

            @if ($torrent->personal_release == 1)
                <i
                        class='{{ config('other.font-awesome') }} fa-user-plus torrent-flag__personal'
                        style="color: #865be9;"
                        title='Personal Release'
                ></i>
            @endif

            @if ($torrent->stream == 1)
                <i
                        class='{{ config('other.font-awesome') }} fa-play text-red torrent-flag__stream-optimized'
                        title='{{ __('torrent.stream-optimized') }}'
                ></i>
            @endif

            @if ($torrent->featured == 0)
                @if ($torrent->doubleup == 1)
                    <i
                            class='{{ config('other.font-awesome') }} fa-gem text-green torrent-flag__double-upload'
                            title='{{ __('torrent.double-upload') }}'
                    ></i>
                @endif

                @if ($torrent->free >= '90')
                    <i
                            class="{{ config('other.font-awesome') }} fa-star text-gold torrent-flag__freeleech"
                            title='{{ $torrent->free }}% {{ __('common.free') }}'
                    ></i>
                @elseif ($torrent->free < '90' && $torrent->free >= '30')
                    <style>
                            .star50 {
                                position: relative;
                            }

                            .star50:after {
                                content: "\f005";
                                position: absolute;
                                left: 0;
                                top: 0;
                                width: 50%;
                                overflow: hidden;
                                color: #FFB800;
                            }
                        </style>
                    <i
                            class="star50 {{ config('other.font-awesome') }} fa-star torrent-flag__freeleech"
                            title='{{ $torrent->free }}% {{ __('common.free') }}'
                    ></i>
                @elseif ($torrent->free < '30' && $torrent->free != '0')
                    <style>
                            .star30 {
                                position: relative;
                            }

                            .star30:after {
                                content: "\f005";
                                position: absolute;
                                left: 0;
                                top: 0;
                                width: 30%;
                                overflow: hidden;
                                color: #FFB800;
                            }
                        </style>
                    <i
                            class="star30 {{ config('other.font-awesome') }} fa-star torrent-flag__freeleech"
                            title='{{ $torrent->free }}% {{ __('common.free') }}'
                    ></i>
                @endif
            @endif

            @if ($personalFreeleech)
                <i
                        class='{{ config('other.font-awesome') }} fa-id-badge text-orange torrent-flag__personal-freeleech'
                        title='{{ __('torrent.personal-freeleech') }}'
                ></i>
            @endif

            @if ($user->freeleechTokens->where('torrent_id', $torrent->id)->first())
                <i
                        class='{{ config('other.font-awesome') }} fa-star text-bold torrent-flag__freeleech-token'
                        title='{{ __('torrent.freeleech-token') }}'
                ></i>
            @endif

            @if ($torrent->featured == 1)
                <span style='background-image:url(/img/sparkels.gif);'>
                        <i
                                class='{{ config('other.font-awesome') }} fa-certificate text-pink torrent-flag__featured'
                                title='{{ __('torrent.featured') }}'
                        ></i>
                    </span>
            @endif

            @if ($user->group->is_freeleech == 1)
                <i
                        class='{{ config('other.font-awesome') }} fa-trophy text-purple torrent-flag__special-freeleech'
                        title='{{ __('torrent.special-freeleech') }}'
                ></i>
            @endif

            @if (config('other.freeleech') == 1)

                <i
                        class='{{ config('other.font-awesome') }} fa-globe text-blue torrent-flag__global-freeleech'
                        title='{{ __('torrent.global-freeleech') }}'
                ></i>
            @endif

            @if (config('other.doubleup') == 1)
                <i
                        class='{{ config('other.font-awesome') }} fa-globe text-green torrent-flag__global-double-upload'
                        title='{{ __('torrent.global-double-upload') }}'
                ></i>
            @endif

            @if ($user->group->is_double_upload == 1)
                <i
                        class='{{ config('other.font-awesome') }} fa-trophy text-purple torrent-flag__special-double-upload'
                        title='{{ __('torrent.special-double_upload') }}'
                ></i>
            @endif

            @if ($torrent->leechers >= 5)
                <i
                        class='{{ config('other.font-awesome') }} fa-fire text-orange torrent-flag__hot'
                        title='{{ __('common.hot') }}'
                ></i>
            @endif

            @if ($torrent->sticky == 1)
                <i
                        class='{{ config('other.font-awesome') }} fa-thumbtack text-black torrent-flag__sticky'
                        title='{{ __('torrent.sticky') }}'
                ></i>
            @endif

            @if ($torrent->highspeed == 1)
                <i
                        class='{{ config('other.font-awesome') }} fa-tachometer text-red torrent-flag__high-speed'
                        title='{{ __('common.high-speeds') }}'
                ></i>
            @endif

            @if ($torrent->sd == 1)
                <i
                        class='{{ config('other.font-awesome') }} fa-ticket text-orange torrent-flag__sd'
                        title='{{ __('torrent.sd-content') }}'
                ></i>
            @endif

            @if ($torrent->bumped_at != $torrent->created_at && $torrent->bumped_at < Illuminate\Support\Carbon::now()->addDay(2))
                <i
                        class='{{ config('other.font-awesome') }} fa-level-up-alt text-gold torrent-flag__bumped'
                        title='{{ __('torrent.recent-bumped') }}'
                ></i>
            @endif
            </span>
    </div>
</td>
<td class="torrent-search--grouped__download">
    @if (config('torrent.download_check_page') == 1)
        <a
                download
                href="{{ route('download_check', ['id' => $torrent->id]) }}"
                title="{{ __('common.download') }}"
        >
            <i class="{{ config('other.font-awesome') }} fa-arrow-alt-to-bottom"></i>
        </a>
    @else
        <a
                download
                href="{{ route('download', ['id' => $torrent->id]) }}"
                title="{{ __('common.download') }}"
        >
            <i class="{{ config('other.font-awesome') }} fa-arrow-alt-to-bottom"></i>
        </a>
    @endif
    @if (config('torrent.magnet') == 1)
        <a
                href="magnet:?dn={{ $torrent->name }}&xt=urn:btih:{{ $torrent->info_hash }}&as={{ route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => $user->rsskey ]) }}&tr={{ route('announce', ['passkey' => $user->passkey]) }}&xl={{ $torrent->size }}"
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
    <time
            datetime="{{ $torrent->created_at }}"
            title="{{ $torrent->created_at }}"
    >
        {{ $torrent->created_at->diffForHumans() }}
    </time>
</td>