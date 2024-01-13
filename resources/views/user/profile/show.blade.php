@extends('layout.default')

@section('title')
    <title>
        {{ $user->username }} - {{ __('common.members') }} - {{ config('other.title') }}
    </title>
@endsection

@section('meta')
    <meta
        name="description"
        content="{{ __('user.profile-desc', ['user' => $user->username, 'title' => config('other.title')]) }}"
    />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ $user->username }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@if (auth()->user()->isAllowed($user))
    @section('main')
        <section class="panelV2">
            <header class="panel__header">
                <h2 class="panel__heading">{{ __('user.user') }} {{ __('user.information') }}</h2>
                <div class="panel__actions">
                    @if (auth()->user()->is($user))
                        <div class="panel__action">
                            <a
                                href="{{ route('users.edit', ['user' => $user]) }}"
                                class="form__button form__button--text"
                            >
                                {{ __('common.edit') }}
                            </a>
                        </div>
                    @elseif (auth()->user()->group->is_modo)
                        <div class="panel__action">
                            <a
                                href="{{ route('staff.users.edit', ['user' => $user]) }}"
                                class="form__button form__button--text"
                            >
                                {{ __('common.edit') }}
                            </a>
                        </div>
                        <div class="panel__action">
                            <form
                                action="{{ route('staff.users.destroy', ['user' => $user]) }}"
                                method="POST"
                                x-data
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    x-on:click.prevent="
                                        Swal.fire({
                                            title: 'Are you sure?',
                                            text: `Are you sure you want to delete this user and all their associated records: ${atob(
                                                '{{ base64_encode($user->username) }}'
                                            )}?`,
                                            icon: 'warning',
                                            showConfirmButton: true,
                                            showCancelButton: true,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $root.submit();
                                            }
                                        })
                                    "
                                    class="form__button form__button--text"
                                >
                                    {{ __('common.delete') }}
                                </button>
                            </form>
                        </div>
                    @endif
                    @if (auth()->id() !== $user->id)
                        <div class="panel__action" x-data>
                            <button
                                class="form__button form__button--text"
                                x-on:click.stop="$refs.dialog.showModal();"
                            >
                                Report
                            </button>
                            <dialog class="dialog" x-ref="dialog">
                                <h3 class="dialog__heading">Report user: {{ $user->username }}</h3>
                                <form
                                    class="dialog__form"
                                    method="POST"
                                    action="{{ route('report_user', ['username' => $user->username]) }}"
                                    x-on:click.outside="$refs.dialog.close();"
                                >
                                    @csrf
                                    <p class="form__group">
                                        <textarea
                                            id="report_reason"
                                            class="form__textarea"
                                            name="message"
                                            required
                                        ></textarea>
                                        <label
                                            class="form__label form__label--floating"
                                            for="report_reason"
                                        >
                                            Reason
                                        </label>
                                    </p>
                                    <p class="form__group">
                                        <button class="form__button form__button--filled">
                                            {{ __('common.save') }}
                                        </button>
                                        <button
                                            formmethod="dialog"
                                            formnovalidate
                                            class="form__button form__button--outlined"
                                        >
                                            {{ __('common.cancel') }}
                                        </button>
                                    </p>
                                </form>
                            </dialog>
                        </div>
                    @endif
                </div>
            </header>
            <div class="panel__body">
                <article class="profileV2">
                    <x-user_tag :user="$user" :anon="false" class="profile__username">
                        <x-slot:appendedIcons>
                            @if ($user->isOnline())
                                <i
                                    class="{{ config('other.font-awesome') }} fa-circle text-green"
                                    title="{{ __('user.online') }}"
                                ></i>
                            @else
                                <i
                                    class="{{ config('other.font-awesome') }} fa-circle text-red"
                                    title="{{ __('user.offline') }}"
                                ></i>
                            @endif
                            <a
                                href="{{ route('users.sent_messages.create', ['user' => auth()->user(), 'username' => $user->username]) }}"
                            >
                                <i
                                    class="{{ config('other.font-awesome') }} fa-envelope text-info"
                                ></i>
                            </a>
                            @if ($user->warnings()->active()->exists())
                                <i
                                    class="{{ config('other.font-awesome') }} fa-exclamation-circle text-orange"
                                    aria-hidden="true"
                                    title="{{ __('user.active-warning') }}"
                                ></i>
                            @endif
                        </x-slot>
                    </x-user_tag>
                    <time
                        datetime="{{ $user->created_at }}"
                        title="{{ $user->created_at }}"
                        class="profile__registration"
                    >
                        {{ __('user.registration-date') }}:
                        {{ $user->created_at?->format('Y-m-d') ?? 'N/A' }}
                    </time>
                    <img
                        src="{{ url($user->image === null ? 'img/profile.png' : 'files/img/' . $user->image) }}"
                        alt="{{ $user->username }}"
                        class="profile__avatar"
                    />
                    @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_title') && $user->title)
                        <span class="profile__title">
                            {{ __('user.title') }}: {{ $user->title }}
                        </span>
                    @endif

                    @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_about') && $user->about)
                        <div class="profile__about">
                            {{ __('user.about') }}:
                            <div class="bbcode-rendered">@joypixels($user->about_html)</div>
                        </div>
                    @endif
                </article>
            </div>
        </section>
        @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_achievement'))
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('user.recent-achievements') }}</h2>
                <div class="panel__body">
                    @forelse ($achievements->take(25) as $achievement)
                        <img
                            src="/img/badges/{{ $achievement->details->name }}.png"
                            title="{{ $achievement->details->name }}"
                            height="50px"
                            alt="{{ $achievement->details->name }}"
                        />
                    @empty
                        No recent achievements.
                    @endforelse
                </div>
            </section>
        @endif

        @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_follower'))
            <section class="panelV2">
                <header class="panel__header">
                    <h2 class="panel__heading">{{ __('user.recent-followers') }}</h2>
                    @if (auth()->id() !== $user->id)
                        <div class="panel__actions">
                            @if ($user->followers()->where('users.id', '=', auth()->id())->exists())
                                <form
                                    action="{{ route('users.followers.destroy', ['user' => $user]) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="form__button form__button--text"
                                        id="delete-follow-{{ $user->target_id }}"
                                    >
                                        {{ __('user.unfollow') }}
                                    </button>
                                </form>
                            @else
                                <form
                                    action="{{ route('users.followers.store', ['user' => $user]) }}"
                                    method="POST"
                                >
                                    @csrf
                                    <button
                                        class="form__button form__button--text"
                                        id="follow-user-{{ $user->id }}"
                                    >
                                        {{ __('user.follow') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </header>
                <div class="panel__body">
                    @forelse ($followers as $follower)
                        <a href="{{ route('users.show', ['user' => $follower]) }}">
                            <img
                                class="user-search__avatar"
                                alt="{{ $follower->username }}"
                                height="50px"
                                src="{{ url($follower->image === null ? 'img/profile.png' : 'files/img/' . $follower->image) }}"
                                title="{{ $follower->username }}"
                            />
                        </a>
                    @empty
                        No recent followers
                    @endforelse
                </div>
            </section>
        @endif

        @if (auth()->user()->is($user) || auth()->user()->group->is_modo)
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('user.client-list') }}</h2>
                <div class="data-table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('torrent.client') }}</th>
                                <th>{{ __('common.ip') }}</th>
                                <th>{{ __('common.port') }}</th>
                                <th>{{ __('torrent.started') }}</th>
                                <th>{{ __('torrent.last-update') }}</th>
                                <th>{{ __('torrent.peers') }}</th>
                                @if (\config('announce.connectable_check') === true)
                                    <th>Connectable</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clients as $client)
                                <tr>
                                    <td>{{ $client->agent }}</td>
                                    <td>
                                        @if (auth()->user()->group->is_modo)
                                            <a
                                                href="{{ route('staff.peers.index', ['ip' => $client->ip, 'groupBy' => 'user_ip']) }}"
                                            >
                                                {{ $client->ip }}
                                            </a>
                                        @elseif (auth()->id() === $user->id)
                                            {{ $client->ip }}
                                        @endif
                                    </td>
                                    <td>{{ $client->port }}</td>
                                    <td>
                                        <time
                                            datetime="{{ $client->created_at }}"
                                            title="{{ $client->created_at }}"
                                        >
                                            {{ $client->created_at?->diffForHumans() ?? 'N/A' }}
                                        </time>
                                    </td>
                                    <td>
                                        <time
                                            datetime="{{ $client->updated_at }}"
                                            title="{{ $client->updated_at }}"
                                        >
                                            {{ $client->updated_at?->diffForHumans() ?? 'N/A' }}
                                        </time>
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('users.peers.index', ['user' => $user, 'ip' => $client->ip, 'port' => $client->port, 'client' => $client->agent]) }}"
                                        >
                                            {{ $client->num_peers }}
                                        </a>
                                    </td>
                                    @if (\config('announce.connectable_check') == true)
                                        @php
                                            $connectable = false;
                                            if (cache()->has('peers:connectable:' . $client->ip . '-' . $client->port . '-' . $client->agent)) {
                                                $connectable = cache()->get('peers:connectable:' . $client->ip . '-' . $client->port . '-' . $client->agent);
                                            }
                                        @endphp

                                        <td>
                                            @choice('user.client-connectable-state', $connectable)
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="{{ \config('announce.connectable_check') === true ? 7 : 6 }}"
                                    >
                                        No Clients
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td
                                    colspan="{{ 5 + (int) auth()->user()->group->is_modo + (int) config('announce.connectable_check') }}"
                                >
                                    If you don't recognize a torrent client or IP address in the
                                    list, please
                                    <a href="{{ route('tickets.index') }}">
                                        create a helpdesk ticket
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        @endif

        @if (auth()->user()->group->is_modo)
            @livewire('user-notes', ['user' => $user])
            @if ($user->application !== null)
                <section class="panelV2">
                    <h2 class="panel__heading">{{ __('staff.application') }}</h2>
                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('common.email') }}</th>
                                    <th>{{ __('staff.application-type') }}</th>
                                    <th>{{ __('common.created_at') }}</th>
                                    <th>{{ __('common.status') }}</th>
                                    <th>{{ __('common.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td>{{ $user->application->email }}</td>
                                <td>{{ $user->application->type }}</td>
                                <td>
                                    <time
                                        datetime="{{ $user->application->created_at }}"
                                        title="{{ $user->application->created_at }}"
                                    >
                                        {{ $user->application->created_at->diffForHumans() }}
                                    </time>
                                </td>
                                <td>
                                    @switch($user->application->status)
                                        @case(\App\Models\Application::PENDING)
                                            <span class="application--pending">Pending</span>

                                            @break
                                        @case(\App\Models\Application::APPROVED)
                                            <span class="application--approved">Approved</span>

                                            @break
                                        @case(\App\Models\Application::REJECTED)
                                            <span class="application--rejected">Rejected</span>

                                            @break
                                        @default
                                            <span class="application--unknown">Unknown</span>
                                    @endswitch
                                </td>
                                <td>
                                    <menu class="data-table__actions">
                                        <li class="data-table__action">
                                            <a
                                                class="form__button form__button--text"
                                                href="{{ route('staff.applications.show', ['id' => $user->application->id]) }}"
                                            >
                                                {{ __('common.view') }}
                                            </a>
                                        </li>
                                    </menu>
                                </td>
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
        @endif

        @if (auth()->user()->group->is_modo ||auth()->user()->is($user))
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('ticket.helpdesk') }}</h2>
                <div class="data-table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('ticket.subject') }}</th>
                                <th>{{ __('common.status') }}</th>
                                <th>{{ __('ticket.created') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->tickets as $ticket)
                                <tr>
                                    <td>
                                        <a
                                            href="{{ route('tickets.show', ['ticket' => $ticket]) }}"
                                        >
                                            {{ $ticket->subject }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($ticket->closed_at)
                                            <i class="fas fa-circle text-danger"></i>
                                            Closed
                                        @else
                                            <i class="fas fa-circle text-success"></i>
                                            Open
                                        @endif
                                    </td>
                                    <td>{{ $ticket->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        @if (auth()->user()->group->is_modo)
            @include('user.profile.partials.bans', ['bans' => $user->userban])
        @endif

        @if (auth()->user()->group->is_modo ||auth()->user()->is($user))
            <livewire:user-warnings :user="$user" />
        @endif

        @if (auth()->user()->group->is_modo)
            <section class="panelV2">
                <header class="panel__header">
                    <h2 class="panel__heading">Watchlist</h2>
                    <div class="panel__actions">
                        @if ($watch === null)
                            <div class="panel__action" x-data="{ open: false }">
                                <button
                                    class="form__button form__button--text"
                                    x-on:click.stop="
                                        open = true;
                                        $refs.dialog.showModal();
                                    "
                                >
                                    Watch
                                </button>
                                <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
                                    <h3 class="dialog__heading">
                                        Watch user: {{ $user->username }}
                                    </h3>
                                    <form
                                        class="dialog__form"
                                        method="POST"
                                        action="{{ route('staff.watchlist.store') }}"
                                        x-on:click.outside="
                                            open = false;
                                            $refs.dialog.close();
                                        "
                                    >
                                        @csrf
                                        <input
                                            type="hidden"
                                            name="user_id"
                                            value="{{ $user->id }}"
                                        />
                                        <p class="form__group">
                                            <textarea
                                                id="watchlist_reason"
                                                class="form__textarea"
                                                name="message"
                                                required
                                            ></textarea>
                                            <label
                                                class="form__label form__label--floating"
                                                for="watchlist_reason"
                                            >
                                                Reason
                                            </label>
                                        </p>
                                        <p class="form__group">
                                            <button class="form__button form__button--filled">
                                                {{ __('common.save') }}
                                            </button>
                                            <button
                                                x-on:click.prevent="
                                                    open = false;
                                                    $refs.dialog.close();
                                                "
                                                class="form__button form__button--outlined"
                                            >
                                                {{ __('common.cancel') }}
                                            </button>
                                        </p>
                                    </form>
                                </dialog>
                            </div>
                        @else
                            <form
                                class="panel__action"
                                action="{{ route('staff.watchlist.destroy', ['watchlist' => $watch]) }}"
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')
                                <button class="form__button form__button--text">Unwatch</button>
                            </form>
                        @endif
                    </div>
                </header>
                <div class="data-table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Watched By</th>
                                <th>Message</th>
                                <th>Created At</th>
                                <th>{{ __('common.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($watch === null)
                                <tr>
                                    <td colspan="4">Not watched</td>
                                </tr>
                            @else
                                <tr>
                                    <td>
                                        <x-user_tag :anon="false" :user="$watch->author" />
                                    </td>
                                    <td>{{ $watch->message }}</td>
                                    <td>
                                        <time
                                            datetime="{{ $watch->created_at }}"
                                            title="{{ $watch->created_at }}"
                                        >
                                            {{ $watch->created_at }}
                                        </time>
                                    </td>
                                    <td>
                                        <menu class="data-table__actions">
                                            <li class="data-table__action">
                                                <form
                                                    action="{{ route('staff.watchlist.destroy', ['watchlist' => $watch]) }}"
                                                    method="POST"
                                                    x-data
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        x-on:click.prevent="
                                                            Swal.fire({
                                                                title: 'Are you sure?',
                                                                text: 'Are you sure you want to unwatch this user: {{ $watch->user->username }}?',
                                                                icon: 'warning',
                                                                showConfirmButton: true,
                                                                showCancelButton: true,
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    $root.submit();
                                                                }
                                                            })
                                                        "
                                                        class="form__button form__button--text"
                                                    >
                                                        Unwatch
                                                    </button>
                                                </form>
                                            </li>
                                        </menu>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    @endsection

    @section('sidebar')
        @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_warning'))
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('common.warnings') }}</h2>
                <dl class="key-value">
                    <dt>{{ __('user.active-warnings') }}</dt>
                    <dd>{{ $user->active_warnings_count ?? 0 }}</dd>
                    <dt>{{ __('user.hit-n-runs-count') }}</dt>
                    <dd>{{ $user->hitandruns }}</dd>
                </dl>
            </section>
        @endif

        @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_torrent_seed'))
            <section class="panelV2">
                <h2 class="panel__heading">Seed {{ __('user.statistics') }}</h2>
                <dl class="key-value">
                    <dt>
                        <abbr
                            title="{{ __('user.total-seedtime') }} ({{ __('user.all-torrents') }})"
                        >
                            {{ __('user.total-seedtime') }}
                        </abbr>
                    </dt>
                    <dd>
                        {{ App\Helpers\StringHelper::timeElapsed($history->seedtime_sum ?? 0) }}
                    </dd>
                    <dt>
                        <abbr
                            title="{{ __('user.avg-seedtime') }} ({{ __('user.per-torrent') }})"
                        >
                            {{ __('user.avg-seedtime') }}
                        </abbr>
                    </dt>

                    <dd>
                        {{ App\Helpers\StringHelper::timeElapsed(($history->seedtime_sum ?? 0) / max(1, $history->count ?? 0)) }}
                    </dd>
                    <dt>
                        <abbr
                            title="{{ __('user.seeding-size') }} ({{ __('user.all-torrents') }})"
                        >
                            {{ __('user.seeding-size') }}
                        </abbr>
                    </dt>
                    <dd>
                        {{ App\Helpers\StringHelper::formatBytes($user->seedingTorrents()->sum('size'), 2) }}
                    </dd>
                </dl>
            </section>
        @endif

        @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_torrent_count'))
            <section class="panelV2">
                <h2 class="panel__heading">Torrent Count</h2>
                <dl class="key-value">
                    <dt>
                        <a href="{{ route('users.torrents.index', ['user' => $user]) }}">
                            {{ __('user.total-uploads') }}
                        </a>
                    </dt>
                    <dd>{{ $user->torrents_count ?? 0 }}</dd>
                    <dt>
                        <a
                            href="{{ route('users.history.index', ['user' => $user, 'downloaded' => 'include']) }}"
                        >
                            {{ __('user.total-downloads') }}
                        </a>
                    </dt>
                    <dd>{{ $history->download_count ?? 0 }}</dd>
                    <dt>
                        <a
                            href="{{ route('users.peers.index', ['user' => $user, 'seeding' => 'include']) }}"
                        >
                            {{ __('user.total-seeding') }}
                        </a>
                    </dt>
                    <dd>{{ $peers->seeding ?? 0 }}</dd>
                    <dt>
                        <a
                            href="{{ route('users.peers.index', ['user' => $user, 'seeding' => 'exclude']) }}"
                        >
                            {{ __('user.total-leeching') }}
                        </a>
                    </dt>
                    <dd>{{ $peers->leeching ?? 0 }}</dd>
                    <dt>
                        <a
                            href="{{ route('users.peers.index', ['user' => $user, 'active' => 'exclude']) }}"
                        >
                            Total Inactive Peers
                        </a>
                    </dt>
                    <dd>{{ $peers->inactive ?? 0 }}</dd>
                </dl>
            </section>
        @endif

        @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_torrent_ratio'))
            <section class="panelV2">
                <h2 class="panel__heading">Traffic {{ __('torrent.statistics') }}</h2>
                <dl class="key-value">
                    <dt>{{ __('common.ratio') }}</dt>
                    <dd>{{ $user->formatted_ratio }}</dd>
                    <dt>Real {{ __('common.ratio') }}</dt>
                    <dd>
                        {{ $history->download_sum ? round(($history->upload_sum ?? 0) / $history->download_sum, 2) : "\u{221E}" }}
                    </dd>
                    <dt>{{ __('common.buffer') }}</dt>
                    <dd>{{ $user->formatted_buffer }}</dd>
                    <dt>{{ __('common.account') }} {{ __('common.upload') }} (Total)</dt>
                    <dd>{{ $user->formatted_uploaded }}</dd>
                    <dt>{{ __('common.account') }} {{ __('common.download') }} (Total)</dt>
                    <dd>{{ $user->formatted_downloaded }}</dd>
                    <dt>{{ __('torrent.torrent') }} {{ __('common.upload') }}</dt>
                    <dd>
                        {{ App\Helpers\StringHelper::formatBytes($history->upload_sum ?? 0, 2) }}
                    </dd>
                    <dt>
                        {{ __('torrent.torrent') }} {{ __('common.upload') }}
                        ({{ __('torrent.credited') }})
                    </dt>
                    <dd>
                        {{ App\Helpers\StringHelper::formatBytes($history->credited_upload_sum ?? 0, 2) }}
                    </dd>
                    <dt>{{ __('torrent.torrent') }} {{ __('common.download') }}</dt>
                    <dd>
                        {{ App\Helpers\StringHelper::formatBytes($history->download_sum ?? 0, 2) }}
                    </dd>
                    <dt>
                        {{ __('torrent.torrent') }} {{ __('common.download') }}
                        ({{ __('torrent.credited') }})
                    </dt>
                    <dd>
                        {{ App\Helpers\StringHelper::formatBytes($history->credited_download_sum ?? 0, 2) }}
                    </dd>
                    <dt>
                        {{ __('torrent.torrent') }} {{ __('common.download') }}
                        ({{ __('torrent.refunded') }})
                    </dt>
                    <dd>
                        {{ App\Helpers\StringHelper::formatBytes($history->refunded_download_sum ?? 0, 2) }}
                    </dd>
                    <dt>{{ __('bon.bon') }} {{ __('common.upload') }}</dt>
                    <dd>{{ App\Helpers\StringHelper::formatBytes($boughtUpload, 2) }}</dd>
                </dl>
            </section>
        @endif

        @if (config('announce.external_tracker.is_enabled') && auth()->user()->group->is_modo)
            @if ($externalUser === true)
                <section class="panelV2">
                    <h2 class="panel__heading">{{ __('torrent.torrent') }}</h2>
                    <div class="panel__body">External tracker not enabled.</div>
                </section>
            @elseif ($externalUser === false)
                <section class="panelV2">
                    <h2 class="panel__heading">{{ __('torrent.torrent') }}</h2>
                    <div class="panel__body">User not found.</div>
                </section>
            @elseif ($externalUser === [])
                <section class="panelV2">
                    <h2 class="panel__heading">{{ __('torrent.torrent') }}</h2>
                    <div class="panel__body">Tracker returned an error.</div>
                </section>
            @else
                <section class="panelV2">
                    <h2 class="panel__heading">External Tracker</h2>
                    <dl class="key-value">
                        <dt>{{ __('common.group') }}</dt>
                        <dd>
                            @if (null !== ($group = \App\Models\Group::find($externalUser['group_id'])))
                                <span class="user-tag">
                                    <a
                                        class="user-tag__link {{ $group->icon }}"
                                        href="{{ route('group', ['id' => $group->id]) }}"
                                        style="color: {{ $group->color }}"
                                        title="{{ $group->name }}"
                                    >
                                        {{ $group->name }}
                                    </a>
                                </span>
                            @else
                                Unrecognized group_id: {{ $externalUser['group_id'] }}
                            @endif
                        </dd>
                        <dt>{{ __('user.passkey') }}</dt>
                        <dd>
                            <details>
                                <summary style="cursor: pointer">
                                    {{ __('user.show-passkey') }}
                                </summary>
                                <code><pre>{{ $externalUser['passkey'] }}</pre></code>
                                <span class="text-red">{{ __('user.passkey-warning') }}</span>
                            </details>
                        </dd>
                        <dt>{{ __('user.can-download') }}</dt>
                        <dd>
                            {{ $externalUser['can_download'] ? __('common.yes') : __('common.no') }}
                        </dd>
                        <dt>{{ __('user.total-seeding') }}</dt>
                        <dd>{{ $externalUser['num_seeding'] }}</dd>
                        <dt>{{ __('user.total-leeching') }}</dt>
                        <dd>{{ $externalUser['num_leeching'] }}</dd>
                    </dl>
                </section>
            @endif
        @endif

        @if (auth()->user()->is($user) || auth()->user()->group->is_modo)
            <section class="panelV2">
                <h2 class="panel__heading">
                    {{ __('user.id-permissions') }}
                </h2>
                <dl class="key-value">
                    <dt>{{ __('user.invited-by') }}</dt>
                    <dd>
                        @if ($invitedBy)
                            <x-user_tag :user="$invitedBy->sender" :anon="false" />
                        @else
                            <b>{{ __('user.open-registration') }}</b>
                        @endif
                    </dd>
                    <dt>{{ __('user.passkey') }}</dt>
                    <dd>
                        <details>
                            <summary style="cursor: pointer">
                                {{ __('user.show-passkey') }}
                            </summary>
                            <code><pre>{{ $user->passkey }}</pre></code>
                            <span class="text-red">{{ __('user.passkey-warning') }}</span>
                        </details>
                    </dd>
                    <dt>{{ __('user.user-id') }}</dt>
                    <dd>{{ $user->id }}</dd>
                    <dt>{{ __('common.email') }}</dt>
                    <dd>{{ $user->email }}</dd>
                    <dt>2FA Enabled</dt>
                    <dd>
                        @if ($user->two_factor_confirmed_at !== null)
                            <i class="{{ config('other.font-awesome') }} fa-lock text-green"></i>
                        @else
                            <i
                                class="{{ config('other.font-awesome') }} fa-lock-open text-red"
                            ></i>
                        @endif
                    </dd>
                    <dt>{{ __('user.last-login') }}</dt>
                    <dd>
                        @if ($user->last_login === null)
                            N/A
                        @else
                            <time
                                class="{{ $user->last_login }}"
                                datetime="{{ $user->last_login }}"
                                title="{{ $user->last_login }}"
                            >
                                {{ $user->last_login->diffForHumans() }}
                            </time>
                        @endif
                    </dd>
                    <dt>Last Action</dt>
                    <dd>
                        @if ($user->last_action === null)
                            N/A
                        @else
                            <time
                                class="{{ $user->last_action }}"
                                datetime="{{ $user->last_action }}"
                                title="{{ $user->last_action }}"
                            >
                                {{ $user->last_action->diffForHumans() }}
                            </time>
                        @endif
                    </dd>
                    <dt>{{ __('user.can-upload') }}</dt>
                    <dd>
                        @if ($user->can_upload == 1)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </dd>
                    <dt>{{ __('user.can-download') }}</dt>
                    <dd>
                        @if ($user->can_download == 1)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </dd>
                    <dt>{{ __('user.can-comment') }}</dt>
                    <dd>
                        @if ($user->can_comment == 1)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </dd>
                    <dt>{{ __('user.can-request') }}</dt>
                    <dd>
                        @if ($user->can_request == 1)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </dd>
                    <dt>{{ __('user.can-chat') }}</dt>
                    <dd>
                        @if ($user->can_chat == 1)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </dd>
                    <dt>{{ __('user.can-invite') }}</dt>
                    <dd>
                        @if ($user->can_invite == 1)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </dd>
                    <dt>
                        <a href="{{ route('users.invites.index', ['user' => $user]) }}">
                            {{ __('user.invites') }}
                        </a>
                    </dt>
                    <dd>{{ $user->invites }}</dd>
                </dl>
            </section>
        @endif

        @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_bon_extra'))
            <section class="panelV2">
                <header class="panel__header">
                    <h2 class="panel__heading">{{ __('user.bon') }}</h2>
                    <div class="panel__actions">
                        <div class="panel__action" x-data="{ open: false }">
                            <button
                                class="form__button form__button--text"
                                x-on:click.stop="
                                    open = true;
                                    $refs.dialog.showModal();
                                "
                            >
                                Gift BON
                            </button>
                            <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
                                <h3 class="dialog__heading">Note user: {{ $user->username }}</h3>
                                <form
                                    class="dialog__form"
                                    method="POST"
                                    action="{{ route('users.gifts.store', ['user' => auth()->user()]) }}"
                                    x-on:click.outside="
                                        open = false;
                                        $refs.dialog.close();
                                    "
                                >
                                    @csrf
                                    <input
                                        type="hidden"
                                        name="receiver_username"
                                        value="{{ $user->username }}"
                                    />
                                    <p class="form__group">
                                        <input
                                            id="cost"
                                            class="form__text"
                                            name="cost"
                                            type="text"
                                            pattern="[0-9]*"
                                            inputmode="numeric"
                                            placeholder=" "
                                        />
                                        <label class="form__label form__label--floating" for="cost">
                                            {{ __('bon.amount') }}
                                        </label>
                                    </p>

                                    <p class="form__group">
                                        <textarea
                                            id="comment"
                                            class="form__textarea"
                                            name="comment"
                                            placeholder=" "
                                        ></textarea>
                                        <label
                                            class="form__label form__label--floating"
                                            for="comment"
                                        >
                                            {{ __('pm.message') }}
                                        </label>
                                    </p>
                                    <p class="form__group">
                                        <button class="form__button form__button--filled">
                                            {{ __('bon.gift') }}
                                        </button>
                                        <button
                                            formmethod="dialog"
                                            formnovalidate
                                            class="form__button form__button--outlined"
                                        >
                                            {{ __('common.cancel') }}
                                        </button>
                                    </p>
                                </form>
                            </dialog>
                        </div>
                    </div>
                </header>
                <dl class="key-value">
                    <dt>
                        <a href="{{ route('users.earnings.index', ['user' => $user]) }}">
                            {{ __('bon.bon') }}
                        </a>
                    </dt>
                    <dd>{{ $user->formatted_seedbonus }}</dd>
                    <dt>{{ __('user.tips-received') }}</dt>
                    <dd>
                        {{ \number_format($user->bonReceived()->where('name', '=', 'tip')->sum('cost'), 0, null,"\u{202F}") }}
                    </dd>
                    <dt>{{ __('user.tips-given') }}</dt>
                    <dd>
                        {{ \number_format($user->bonGiven()->where('name', '=', 'tip')->sum('cost'), 0, null,"\u{202F}") }}
                    </dd>
                    <dt>{{ __('user.gift-received') }}</dt>
                    <dd>
                        {{ \number_format($user->bonReceived()->where('name', '=', 'gift')->sum('cost'), 0, null,"\u{202F}") }}
                    </dd>
                    <dt>{{ __('user.gift-given') }}</dt>
                    <dd>
                        {{ \number_format($user->bonGiven()->where('name', '=', 'gift')->sum('cost'), 0, null,"\u{202F}") }}
                    </dd>
                    <dt>{{ __('user.bounty-received') }}</dt>
                    <dd>
                        {{ \number_format($user->bonReceived()->where('name', '=', 'request')->sum('cost'), 0, null,"\u{202F}") }}
                    </dd>
                    <dt>{{ __('user.bounty-given') }}</dt>
                    <dd>
                        {{ \number_format($user->bonGiven()->where('name', '=', 'request')->sum('cost'), 0, null,"\u{202F}") }}
                    </dd>
                </dl>
            </section>
        @endif

        @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_torrent_extra'))
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('user.torrents') }}</h2>
                <dl class="key-value">
                    <dt>{{ __('common.fl_tokens') }}</dt>
                    <dd>{{ $user->fl_tokens }}</dd>
                    <dt>{{ __('user.thanks-received') }}</dt>
                    <dd>{{ $user->thanksReceived()->count() }}</dd>
                    <dt>{{ __('user.thanks-given') }}</dt>
                    <dd>{{ $user->thanksGiven()->count() }}</dd>
                    <dt>{{ __('user.upload-snatches') }}</dt>
                    <dd>{{ $user->uploadSnatches()->count() }}</dd>
                </dl>
            </section>
        @endif

        @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_comment_extra'))
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('user.comments') }}</h2>
                <dl class="key-value">
                    <dt>{{ __('user.article-comments') }}</dt>
                    <dd>
                        {{ $user->comments()->whereHasMorph('commentable', [App\Models\Article::class])->count() }}
                    </dd>
                    <dt>{{ __('user.torrent-comments') }}</dt>
                    <dd>
                        {{ $user->comments()->whereHasMorph('commentable', [App\Models\Torrent::class])->count() }}
                    </dd>
                    <dt>{{ __('user.request-comments') }}</dt>
                    <dd>
                        {{ $user->comments()->whereHasMorph('commentable', [App\Models\TorrentRequest::class])->count() }}
                    </dd>
                </dl>
            </section>
        @endif

        @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_forum_extra'))
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('user.forums') }}</h2>
                <dl class="key-value">
                    <dt>
                        <a href="{{ route('users.topics.index', ['user' => $user]) }}">
                            {{ __('user.topics-started') }}
                        </a>
                    </dt>
                    <dd>{{ $user->topics_count }}</dd>
                    <dt>
                        <a href="{{ route('users.posts.index', ['user' => $user]) }}">
                            {{ __('user.posts-posted') }}
                        </a>
                    </dt>
                    <dd>{{ $user->posts_count }}</dd>
                </dl>
            </section>
        @endif

        @if (auth()->user()->isAllowed($user, 'profile', 'show_profile_request_extra'))
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('user.requests') }}</h2>
                <dl class="key-value">
                    <dt>
                        <a href="{{ route('requests.index', ['requestor' => $user->username]) }}">
                            {{ __('user.requested') }}
                        </a>
                    </dt>
                    <dd>{{ $user->requests_count }}</dd>
                    <dt>{{ __('user.filled-request') }}</dt>
                    <dd>{{ $user->filled_requests_count }}</dd>
                </dl>
            </section>
        @endif
    @endsection
@else
    @section('main')
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('user.private-profile') }}</h2>
            <div class="panel__body">{{ __('user.not-authorized') }}</div>
        </section>
    @endsection
@endif
