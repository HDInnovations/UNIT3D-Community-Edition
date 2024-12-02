<nav class="top-nav" x-data="{ expanded: false }" x-bind:class="expanded && 'mobile'">
    <div class="top-nav__left">
        <a class="top-nav__branding" href="{{ route('home.index') }}">
            <img src="{{ url('/favicon.ico') }}" style="height: 35px" />
            <span class="top-nav__site-logo">{{ \config('other.title') }}</span>
        </a>
        @include('partials.quick-search-dropdown')
    </div>
    <ul class="top-nav__main-menus" x-bind:class="expanded && 'mobile'">
        <li class="top-nav--left__list-item top-nav__dropdown">
            <a class="top-nav__dropdown--nontouch" href="{{ route('torrents.index') }}">
                <div class="top-nav--left__container">
                    {{ __('torrent.torrents') }}
                </div>
            </a>
            <a class="top-nav__dropdown--touch" tabindex="0">
                <div class="top-nav--left__container">
                    {{ __('torrent.torrents') }}
                </div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('torrents.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-download"></i>
                        {{ __('torrent.torrents') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('torrents.create') }}">
                        <i class="{{ config('other.font-awesome') }} fa-upload"></i>
                        {{ __('common.upload') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('requests.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-hands-helping"></i>
                        {{ __('request.requests') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('rss.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-rss"></i>
                        {{ __('rss.rss') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('mediahub.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-database"></i>
                        MediaHub
                    </a>
                </li>
            </ul>
        </li>
        <li class="top-nav--left__list-item top-nav__dropdown">
            <a class="top-nav__dropdown--nontouch" href="{{ route('forums.index') }}">
                <div class="top-nav--left__container">
                    {{ __('common.community') }}
                </div>
            </a>
            <a class="top-nav__dropdown--touch" tabindex="0">
                <div class="top-nav--left__container">
                    {{ __('common.community') }}
                </div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('forums.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-comments"></i>
                        {{ __('forum.forums') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('playlists.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-list-ol"></i>
                        {{ __('playlist.playlists') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('polls.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-chart-pie"></i>
                        {{ __('poll.polls') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('stats') }}">
                        <i class="{{ config('other.font-awesome') }} fa-chart-bar"></i>
                        {{ __('common.extra-stats') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('articles.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-newspaper"></i>
                        {{ __('common.news') }}
                    </a>
                </li>
                @if (! empty(config('unit3d.chat-link-url')))
                    <li>
                        <a href="{{ config('unit3d.chat-link-url') }}">
                            <i class="{{ config('unit3d.chat-link-icon') }}"></i>
                            {{ config('unit3d.chat-link-name') ?: __('common.chat') }}
                        </a>
                    </li>
                @endif
            </ul>
        </li>
        <li class="top-nav__dropdown">
            <a tabindex="0">
                <div class="top-nav--left__container">
                    {{ __('common.support') }}
                    <!-- Notifications for Mods -->
                    @if (auth()->user()->group->is_modo)
                        @php
                            $tickets = DB::table('tickets')
                                ->whereNull('closed_at')
                                ->whereNull('staff_id')
                                ->orwhere(function ($query) {
                                    $query
                                        ->where('staff_id', '=', auth()->id())
                                        ->Where('staff_read', '=', false);
                                })
                                ->exists();
                        @endphp

                        @if ($tickets)
                            <x-animation.notification />
                        @endif

                        <!-- Notification for Users -->
                    @else
                        @php
                            $ticket_unread = DB::table('tickets')
                                ->where('user_id', '=', auth()->id())
                                ->where('user_read', '=', false)
                                ->exists();
                        @endphp

                        @if ($ticket_unread)
                            <x-animation.notification />
                        @endif
                    @endif
                </div>
            </a>
            <ul>
                <li>
                    <a href="{{ config('other.rules_url') }}">
                        <i class="{{ config('other.font-awesome') }} fa-info"></i>
                        {{ __('common.rules') }}
                    </a>
                </li>
                <li>
                    <a href="{{ config('other.faq_url') }}">
                        <i class="{{ config('other.font-awesome') }} fa-question"></i>
                        {{ __('common.faq') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('wikis.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-list-alt"></i>
                        Wiki
                    </a>
                </li>
                <li>
                    <a href="{{ route('tickets.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-life-ring"></i>
                        {{ __('ticket.helpdesk') }}
                        <!-- Notifications for Mods -->
                        @if (auth()->user()->group->is_modo)
                            @if ($tickets > 0)
                                <x-animation.notification />
                            @endif

                            <!-- Notification for Users -->
                        @else
                            @if ($ticket_unread > 0)
                                <x-animation.notification />
                            @endif
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff') }}">
                        <i class="{{ config('other.font-awesome') }} fa-user-secret"></i>
                        {{ __('common.staff') }}
                    </a>
                </li>
            </ul>
        </li>
        <li class="top-nav__dropdown">
            @php
                $events = \App\Models\Event::query()
                    ->where('active', '=', true)
                    ->withExists([
                        'claimedPrizes' => fn ($query) => $query
                            ->where('created_at', '>', now()->startOfDay())
                            ->where('user_id', '=', auth()->id()),
                    ])
                    ->get()
            @endphp

            <a tabindex="0">
                <div class="top-nav--left__container">
                    {{ __('common.other') }}
                    @if ($events->contains(fn ($event) => ! $event->claimed_prizes_exists && $event->ends_at->endOfDay()->isFuture()))
                        <x-animation.notification />
                    @endif
                </div>
            </a>
            <ul>
                @foreach ($events as $event)
                    <li>
                        <a href="{{ route('events.show', ['event' => $event]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-calendar-star"></i>
                            {{ $event->name }}
                            @if (! $event->claimed_prizes_exists && $event->ends_at->isFuture())
                                <x-animation.notification />
                            @endif
                        </a>
                    </li>
                @endforeach

                <li>
                    <a href="{{ route('subtitles.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-closed-captioning"></i>
                        {{ __('common.subtitles') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('top10.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-trophy-alt"></i>
                        {{ __('common.top-10') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('missing.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-ballot-check"></i>
                        {{ __('common.missing') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('internal') }}">
                        <i class="{{ config('other.font-awesome') }} fa-star-shooting"></i>
                        {{ __('common.internal') }}
                    </a>
                </li>
            </ul>
        </li>
        @if (config('donation.is_enabled'))
            <li class="top-nav__dropdown">
                @php
                    $sum = App\Models\Donation::whereMonth('created_at', date('m'))
                        ->whereYear('created_at', date('Y'))
                        ->where('status', App\Models\Donation::APPROVED)
                        ->with('package')
                        ->get()
                        ->sum(function ($donation) {
                            return $donation->package->cost;
                        });
                    $percentage = $sum ? min(100, number_format(($sum / config('donation.monthly_goal')) * 100)) : 0;
                @endphp

                <a tabindex="0" title="{{ $percentage }}% filled">
                    <div class="top-nav--left__container">
                        <span
                            class="{{ $percentage < 100 ? 'fa-fade' : '' }}"
                            style="color: lightcoral"
                        >
                            Donate
                        </span>
                        <div class="progress" style="background-color: slategray">
                            <div
                                class="progress-bar"
                                role="progressbar"
                                style="
                                    width: {{ $percentage ?? 0 }}%;
                                    background-color: slategray;
                                    border-bottom: 2px solid lightcoral !important;
                                    max-width: 100%;
                                "
                                aria-valuenow="{{ $percentage ?? 0 }}"
                                aria-valuemin="0"
                                aria-valuemax="{{ config('donation.monthly_goal') }}"
                            ></div>
                        </div>
                    </div>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('donations.index') }}">
                            <i class="fas fa-display-chart-up-circle-dollar"></i>
                            Support {{ config('other.title') }} ({{ $percentage ?? 0 }}%)
                        </a>
                    </li>
                    <li>
                        <a href="https://polar.sh/HDInnovations">
                            <i class="fas fa-handshake"></i>
                            Support UNIT3D Development
                        </a>
                    </li>
                </ul>
            </li>
        @endif
    </ul>
    <div class="top-nav__right" x-bind:class="expanded && 'mobile'">
        <ul class="top-nav__stats" x-bind:class="expanded && 'mobile'">
            <li class="top-nav__stats-up" title="{{ __('common.upload') }}">
                <i class="{{ config('other.font-awesome') }} fa-arrow-up text-green"></i>
                {{ auth()->user()->formatted_uploaded }}
            </li>
            <li class="top-nav__stats-down" title="{{ __('common.download') }}">
                <i class="{{ config('other.font-awesome') }} fa-arrow-down text-red"></i>
                {{ auth()->user()->formatted_downloaded }}
            </li>
            <li class="top-nav__stats-ratio" title="{{ __('common.ratio') }}">
                <i class="{{ config('other.font-awesome') }} fa-sync-alt text-blue"></i>
                {{ auth()->user()->formatted_ratio }}
            </li>
        </ul>
        <ul class="top-nav__ratio-bar" x-bind:class="expanded && 'mobile'">
            <li class="ratio-bar__uploaded" title="{{ __('common.upload') }}">
                <a href="{{ route('users.torrents.index', ['user' => auth()->user()]) }}">
                    <i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                    {{ auth()->user()->formatted_uploaded }}
                </a>
            </li>
            <li class="ratio-bar__downloaded" title="{{ __('common.download') }}">
                <a
                    href="{{ route('users.history.index', ['user' => auth()->user(), 'downloaded' => 'include']) }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                    {{ auth()->user()->formatted_downloaded }}
                </a>
            </li>
            @php
                // Generally sites have more seeders than leechers, so it ends up being faster (by approximately 50%) to compute these stats instead of computing them individually
                $peer_count = Cache::remember(
                    'users:' . auth()->id() . ':peer_count',
                    60,
                    fn () => DB::table('peers')
                        ->where('user_id', '=', auth()->id())
                        ->where('active', '=', 1)
                        ->count() ?? 0
                );
                $leech_count = Cache::remember(
                    'users:' . auth()->id() . ':leech_count',
                    60,
                    fn () => DB::table('peers')
                        ->where('seeder', '=', false)
                        ->where('user_id', '=', auth()->id())
                        ->where('active', '=', 1)
                        ->count() ?? 0
                );
            @endphp

            <li class="ratio-bar__seeding" title="{{ __('torrent.seeding') }}">
                <a href="{{ route('users.peers.index', ['user' => auth()->user()]) }}">
                    <i class="{{ config('other.font-awesome') }} fa-upload"></i>
                    {{ $peer_count - $leech_count }}
                </a>
            </li>
            <li class="ratio-bar__leeching" title="{{ __('torrent.leeching') }}">
                <a
                    href="{{ route('users.peers.index', ['user' => auth()->user(), 'seeding' => 'exclude']) }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-download"></i>
                    {{ $leech_count }}
                </a>
            </li>
            <li class="ratio-bar__buffer" title="{{ __('common.buffer') }}">
                <a href="{{ route('users.history.index', ['user' => auth()->user()]) }}">
                    <i class="{{ config('other.font-awesome') }} fa-exchange"></i>
                    {{ auth()->user()->formatted_buffer }}
                </a>
            </li>
            <li class="ratio-bar__points" title="{{ __('user.my-bonus-points') }}">
                <a href="{{ route('users.earnings.index', ['user' => auth()->user()]) }}">
                    <i class="{{ config('other.font-awesome') }} fa-coins"></i>
                    {{ auth()->user()->formatted_seedbonus }}
                </a>
            </li>
            <li class="ratio-bar__ratio" title="{{ __('common.ratio') }}">
                <a href="{{ route('users.history.index', ['user' => auth()->user()]) }}">
                    <i class="{{ config('other.font-awesome') }} fa-sync-alt"></i>
                    {{ auth()->user()->formatted_ratio }}
                </a>
            </li>
            <li class="ratio-bar__tokens" title="{{ __('user.my-fl-tokens') }}">
                <a href="{{ route('users.show', ['user' => auth()->user()]) }}">
                    <i class="{{ config('other.font-awesome') }} fa-star"></i>
                    {{ auth()->user()->fl_tokens }}
                </a>
            </li>
        </ul>
        <a
            class="top-nav__username--highresolution"
            href="{{ route('users.show', ['user' => auth()->user()]) }}"
        >
            <span
                class="text-bold"
                style="
                    color: {{ auth()->user()->group->color }};
                    background-image: {{ auth()->user()->group->effect }};
                "
            >
                <i class="{{ auth()->user()->group->icon }}"></i>
                {{ auth()->user()->username }}
                @if ($warningsExists = auth()->user()->warnings()->active()->exists())
                    <i
                        class="{{ config('other.font-awesome') }} fa-exclamation-circle text-orange"
                        title="{{ __('common.active-warning') }}"
                    ></i>
                @endif
            </span>
        </a>
        <ul class="top-nav__icon-bar" x-bind:class="expanded && 'mobile'">
            @if (auth()->user()->group->is_modo)
                <li>
                    <a
                        class="top-nav--right__icon-link"
                        href="{{ route('staff.dashboard.index') }}"
                        title="{{ __('staff.staff-dashboard') }}"
                    >
                        <i class="{{ config('other.font-awesome') }} fa-cogs"></i>
                        @php
                            $unsolvedReportsCount = DB::table('reports')
                                ->whereNull('snoozed_until')
                                ->where('solved', '=', false)
                                ->count()
                        @endphp

                        @if ($unsolvedReportsCount > 0)
                            <x-animation.notification />
                        @endif
                    </a>
                </li>
            @endif

            @if (auth()->user()->group->is_torrent_modo)
                <li>
                    <a
                        class="top-nav--right__icon-link"
                        href="{{ route('staff.moderation.index') }}"
                        title="{{ __('staff.torrent-moderation') }}"
                    >
                        <i class="{{ config('other.font-awesome') }} fa-tasks"></i>
                        @php
                            $torrents_unmoderated = DB::table('torrents')
                                ->where('status', '=', \App\Models\Torrent::PENDING)
                                ->whereNull('deleted_at')
                                ->exists();
                        @endphp

                        @if ($torrents_unmoderated)
                            <x-animation.notification />
                        @endif
                    </a>
                </li>
            @endif

            <li>
                @php
                    $pm = DB::table('participants')
                        ->where('user_id', '=', auth()->id())
                        ->where('read', '=', false)
                        ->whereNull('deleted_at')
                        ->exists();
                @endphp

                <a
                    class="top-nav--right__icon-link"
                    href="{{ route('users.conversations.index', ['user' => auth()->user()]) }}"
                    title="{{ __('pm.inbox') }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-envelope"></i>
                    @if ($pm)
                        <x-animation.notification />
                    @endif
                </a>
            </li>
            <li>
                <a
                    class="top-nav--right__icon-link"
                    href="{{ route('users.notifications.index', ['user' => auth()->user()]) }}"
                    title="{{ __('user.notifications') }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-bell"></i>
                    @if (auth()->user()->unreadNotifications()->exists())
                        <x-animation.notification />
                    @endif
                </a>
            </li>
            <li class="top-nav__dropdown">
                <a
                    class="top-nav__dropdown--nontouch"
                    href="{{ route('users.show', ['user' => auth()->user()]) }}"
                >
                    <img
                        src="{{ url(auth()->user()->image ? 'files/img/' . auth()->user()->image : 'img/profile.png') }}"
                        alt="{{ __('user.my-profile') }}"
                        class="top-nav__profile-image"
                    />
                </a>
                <a class="top-nav__dropdown--touch" tabindex="0">
                    <img
                        src="{{ url(auth()->user()->image ? 'files/img/' . auth()->user()->image : 'img/profile.png') }}"
                        alt="{{ __('user.my-profile') }}"
                        class="top-nav__profile-image"
                    />
                </a>
                <ul>
                    <li>
                        <a
                            class="top-nav__username"
                            href="{{ route('users.show', ['user' => auth()->user()]) }}"
                        >
                            <span
                                class="text-bold"
                                style="
                                    color: {{ auth()->user()->group->color }};
                                    background-image: {{ auth()->user()->group->effect }};
                                "
                            >
                                <i class="{{ auth()->user()->group->icon }}"></i>
                                {{ auth()->user()->username }}
                                @if ($warningsExists)
                                    <i
                                        class="{{ config('other.font-awesome') }} fa-exclamation-circle text-orange"
                                        title="{{ __('common.active-warning') }}"
                                    ></i>
                                @endif
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.show', ['user' => auth()->user()]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-user"></i>
                            {{ __('user.my-profile') }}
                        </a>
                    </li>
                    <li>
                        <a
                            class="top-nav--right__link"
                            href="{{ route('users.general_settings.edit', ['user' => auth()->user()]) }}"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-cogs"></i>
                            {{ __('user.my-settings') }}
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('users.privacy_settings.edit', ['user' => auth()->user()]) }}"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-eye"></i>
                            {{ __('user.my-privacy') }}
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('users.achievements.index', ['user' => auth()->user()]) }}"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-trophy-alt"></i>
                            {{ __('user.my-achievements') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.torrents.index', ['user' => auth()->user()]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-upload"></i>
                            @php
                                $uploadCount = Cache::remember(
                                    'users:' . auth()->id() . ':upload_count',
                                    60,
                                    fn () => auth()
                                        ->user()
                                        ->torrents()
                                        ->count() ?? 0
                                );
                            @endphp

                            {{ __('user.my-uploads') }}

                            @if ($uploadCount > 0)
                                ({{ $uploadCount }})
                            @endif
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('users.history.index', ['user' => auth()->user(), 'downloaded' => 'include']) }}"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                            @php
                                $downloadCount = Cache::remember(
                                    'users:' . auth()->id() . ':download_count',
                                    60,
                                    fn () => auth()
                                        ->user()
                                        ->history()
                                        ->where('actual_downloaded', '>', 0)
                                        ->count() ?? 0
                                );
                            @endphp

                            {{ __('user.my-downloads') }}

                            @if ($downloadCount > 0)
                                ({{ $downloadCount }})
                            @endif
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('requests.index', ['requestor' => auth()->user()->username]) }}"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-question"></i>
                            {{ __('user.my-requested') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('torrents.index', ['bookmarked' => 1]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-bookmark"></i>
                            {{ __('user.my-bookmarks') }}
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('playlists.index', ['username' => auth()->user()->username]) }}"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-list-ol"></i>
                            {{ __('user.my-playlists') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.wishes.index', ['user' => auth()->user()]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-clipboard-list"></i>
                            {{ __('user.my-wishlist') }}
                        </a>
                    </li>
                    <li>
                        <form role="form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="top-nav--right__link" type="submit">
                                <i class="far fa-sign-out"></i>
                                {{ __('auth.logout') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <button
        class="top-nav__toggle {{ \config('other.font-awesome') }}"
        x-bind:class="expanded ? 'fa-times mobile' : 'fa-bars'"
        x-on:click="expanded = !expanded"
    ></button>
</nav>
