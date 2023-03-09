<div class="torrent-icons">
    <i class="{{ config('other.font-awesome') }} fa-heartbeat torrent-icons__thanks">{{ $torrent->thanks_count }}</i>
    <i class="{{ config('other.font-awesome') }} fa-comment-alt-lines torrent-icons__comments">{{ $torrent->comments_count }}</i>
    @if ($torrent->internal)
        <i
            class="{{ config('other.font-awesome') }} fa-magic torrent-icons__internal"
            title="{{ __('torrent.internal-release') }}"
        ></i>
    @endif
    @if ($torrent->personal_release)
        <i
            class="{{ config('other.font-awesome') }} fa-user-plus torrent-icons__personal"
            title="Personal Release"
        ></i>
    @endif
    @if ($torrent->stream)
        <i
            class="{{ config('other.font-awesome') }} fa-play text-red torrent-icons__stream-optimized"
            title="{{ __('torrent.stream-optimized') }}"
        ></i>
    @endif
    @if ($torrent->featured)
        <i
            class="{{ config('other.font-awesome') }} fa-certificate text-pink torrent-icons__featured"
            title="{{ __('torrent.featured') }}"
        ></i>
    @endif
    @php
        $alwaysFreeleech = $personalFreeleech || $torrent->freeleechTokens_exists || auth()->user()->group->is_freeleech || config('other.freeleech')
    @endphp
    @if ($alwaysFreeleech || $torrent->free)
        <i
            @class([
                'torrent-icons__freeleech '.config('other.font-awesome'),
                'fa-star' => $alwaysFreeleech || (90 <= $torrent->free && $torrent->fl_until === null),
                'fa-star-half' => ! $alwaysFreeleech && $torrent->free < 90 && $torrent->fl_until === null,
                'fa-calendar-star' => ! $alwaysFreeleech && $torrent->fl_until !== null,
            ])
            title="@if($personalFreeleech){{ __('torrent.personal-freeleech') }}&NewLine;@endif&ZeroWidthSpace;@if($torrent->freeleechTokens_exists){{ __('torrent.freeleech-token') }}&NewLine;@endif&ZeroWidthSpace;@if(auth()->user()->group->is_freeleech){{ __('torrent.special-freeleech') }}&NewLine;@endif&ZeroWidthSpace;@if(config('other.freeleech')){{ __('torrent.global-freeleech') }}&NewLine;@endif&ZeroWidthSpace;@if($torrent->free > 0){{ $torrent->free }}% {{ __('common.free') }}@if($torrent->fl_until !== null) (expires {{ $torrent->fl_until->diffForHumans() }})@endif&ZeroWidthSpace;@endif"
        ></i>
    @endif
    @if (config('other.doubleup') || auth()->user()->group->is_double_upload || $torrent->doubleup)
        <i
            class="{{ config('other.font-awesome') }} fa-chevron-double-up text-green torrent-icons__double-upload"
            title="@if(config('other.doubleup')){{ __('torrent.global-double-upload') }}&NewLine;@endif&ZeroWidthSpace;@if(auth()->user()->group->is_double_upload){{ __('torrent.special-double_upload') }}&NewLine;@endif&ZeroWidthSpace;@if($torrent->doubleup > 0){{ $torrent->doubleup }}% {{ __('torrent.double-upload') }}@if($torrent->du_until !== null) (expires {{ $torrent->du_until->diffForHumans() }})@endif&ZeroWidthSpace;@endif"
        ></i>
    @endif
    @if ($torrent->sticky)
        <i
            class="{{ config('other.font-awesome') }} fa-thumbtack text-black torrent-icons__sticky"
            title="{{ __('torrent.sticky') }}"
        ></i>
    @endif
    @if ($torrent->highspeed)
        <i
            class="{{ config('other.font-awesome') }} fa-tachometer text-red torrent-icons__highspeed"
            title="{{ __('common.high-speeds') }}"
        ></i>
    @endif
    @if ($torrent->sd)
        <i
            class="{{ config('other.font-awesome') }} fa-ticket text-orange torrent-icons__sd"
            title="{{ __('torrent.sd-content') }}"
        ></i>
    @endif
    @if ($torrent->bumped_at != $torrent->created_at && $torrent->bumped_at < Illuminate\Support\Carbon::now()->addDay(2))
        <i
            class="{{ config('other.font-awesome') }} fa-level-up-alt text-gold torrent-icons__bumped"
            title="{{ __('torrent.recent-bumped') }}: {{ $torrent->bumped_at }}"
        ></i>
    @endif
</div>
