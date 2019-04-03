<div class="ratio-bar">
    <div class="container-fluid">
        <ul class="list-inline">
            <li>
                <a href="{{ route('profile', ['username' => auth()->user()->slug, 'id' => auth()->user()->id]) }}">
                    <span class="badge-user text-bold" style="color:{{ auth()->user()->group->color }};">
                        <strong>{{ auth()->user()->username }}</strong>
                        @if (auth()->user()->getWarning() > 0)
                            <i class="{{ config('other.font-awesome') }} fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip"
                               data-original-title="@lang('common.active-warning')"></i>
                        @endif
                    </span>
                </a>
            </li>
            <li>
                <span class="badge-user text-bold" style="color:{{ auth()->user()->group->color }}; background-image:{{ auth()->user()->group->effect }};">
                    <i class="{{ auth()->user()->group->icon }}"></i>
                    <strong> {{ auth()->user()->group->name }}</strong>
                </span>
            </li>
            <li>
                <span class="badge-user text-bold">
                    <i class="{{ config('other.font-awesome') }} fa-arrow-up text-green"></i>
                    @lang('common.upload') : {{ auth()->user()->getUploaded() }}
                </span>
            </li>
            <li>
                <span class="badge-user text-bold">
                    <i class="{{ config('other.font-awesome') }} fa-arrow-down text-red"></i>
                    @lang('common.download') : {{ auth()->user()->getDownloaded() }}
                </span>
            </li>
            <li>
                <span class="badge-user text-bold">
                    <i class="{{ config('other.font-awesome') }} fa-sync-alt text-blue"></i>
                    @lang('common.ratio') : {{ auth()->user()->getRatioString() }}
                </span>
            </li>
            <li>
                <span class="badge-user text-bold">
                    <i class="{{ config('other.font-awesome') }} fa-exchange text-orange"></i>
                    @lang('common.buffer') : {{ auth()->user()->untilRatio(config('other.ratio')) }}
                </span>
            </li>
            <li>
                <span class="badge-user text-bold">
                    <i class="{{ config('other.font-awesome') }} fa-upload text-green"></i>
                        <a href="{{ route('user_active', ['slug' => auth()->user()->slug, 'id' => auth()->user()->id]) }}"
                            title="@lang('torrent.my-active-torrents')">
                            <span class="text-blue"> @lang('torrent.seeding'):</span>
                        </a>
                    {{ auth()->user()->getSeeding() }}
                </span>
            </li>
            <li>
                <span class="badge-user text-bold">
                    <i class="{{ config('other.font-awesome') }} fa-download text-red"></i>
                        <a href="{{ route('user_active', ['slug' => auth()->user()->slug, 'id' => auth()->user()->id]) }}"
                            title="@lang('torrent.my-active-torrents')">
                            <span class="text-blue"> @lang('torrent.leeching'):</span>
                        </a>
                    {{ auth()->user()->getLeeching() }}
                </span>
            </li>
            <li>
                <span class="badge-user text-bold">
                    <i class="{{ config('other.font-awesome') }} fa-exclamation-circle text-orange"></i>
                        <a href="#" title="@lang('torrent.hit-and-runs')">
                            <span class="text-blue"> @lang('common.warnings'):</span>
                        </a>
                    {{ auth()->user()->getWarning() }}
                </span>
            </li>
            <li>
                <span class="badge-user text-bold">
                    <i class="{{ config('other.font-awesome') }} fa-shopping-cart text-purple"></i>
                        <a href="{{ route('bonus') }}" title="@lang('user.my-bonus-points')">
                            <span class="text-blue"> @lang('bon.bon'):</span>
                        </a>
                    {{ auth()->user()->getSeedbonus() }}
                </span>
            </li>
            <li>
                <span class="badge-user text-bold">
                    <i class="{{ config('other.font-awesome') }} fa-coins text-gold"></i>
                        <a href="{{ route('profile', ['username' => auth()->user()->username, 'id' => auth()->user()->id]) }}"
                            title="@lang('user.my-fl-tokens')">
                            <span class="text-blue"> @lang('common.fl_tokens') :</span>
                        </a>
                    {{ auth()->user()->fl_tokens }}
                </span>
            </li>
        </ul>
    </div>
</div>
