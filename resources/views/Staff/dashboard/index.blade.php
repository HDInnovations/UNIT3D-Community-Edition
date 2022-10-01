@extends('layout.default')

@section('title')
    <title>{{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('staff.staff-dashboard') }}
    </li>
@endsection

@section('page', 'page__dashboard')

@section('main')
    <div class="dashboard__menus">
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-link"></i>
                {{ __('staff.links') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('home.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.frontend') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.dashboard.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.staff-dashboard') }}
                    </a>
                </p>
                @if (auth()->user()->group->is_owner)
                    <p class="form__group form__group--horizontal">
                        <a class="form__button form__button--text" href="{{ route('staff.backups.index') }}">
                            <i class="{{ config('other.font-awesome') }} fa-hdd"></i>
                            {{ __('backup.backup') }}
                            {{ __('backup.manager') }}
                        </a>
                    </p>
                    <p class="form__group form__group--horizontal">
                        <a class="form__button form__button--text" href="{{ route('staff.commands.index') }}">
                            <i class="fab fa-laravel"></i> Commands
                        </a>
                    </p>
                @endif
            </div>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i>
                {{ __('staff.chat-tools') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.statuses.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-comment-dots"></i>
                        {{ __('staff.statuses') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.rooms.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-comment-dots"></i>
                        {{ __('staff.rooms') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.bots.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-robot"></i>
                        {{ __('staff.bots') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <form method="POST" action="{{ route('staff.flush.chat') }}" x-data>
                        @csrf
                        <button 
                            x-on:click.prevent="Swal.fire({
                                title: 'Are you sure?',
                                text: 'Are you sure you want to delete all chatbox messages in all chatrooms (including private chatbox messages)?',
                                icon: 'warning',
                                showConfirmButton: true,
                                showCancelButton: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $root.submit();
                                }
                            })"
                            class="form__button form__button--text" href
                        >
                            <i class="{{ config('other.font-awesome') }} fa-broom"></i>
                            {{ __('staff.flush-chat') }}
                        </button>
                    </form>
                </p>
            </div>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i>
                {{ __('staff.general-tools') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.articles.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-newspaper"></i>
                        {{ __('staff.articles') }}
                    </a>
                </p>
                @if (auth()->user()->group->is_admin)
                    <p class="form__group form__group--horizontal">
                        <a class="form__button form__button--text" href="{{ route('staff.forums.index') }}">
                            <i class="fab fa-wpforms"></i>
                            {{ __('staff.forums') }}
                        </a>
                    </p>
                @endif
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.pages.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.pages') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.polls.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-chart-pie"></i>
                        {{ __('staff.polls') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.bon_exchanges.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-coins"></i>
                        {{ __('staff.bon-exchange') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.blacklists.clients.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-ban"></i>
                        {{ __('common.blacklist') }}
                    </a>
                </p>
            </div>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i>
                {{ __('staff.torrent-tools') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.moderation.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.torrent-moderation') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.categories.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.torrent-categories') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.types.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.torrent-types') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.resolutions.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.torrent-resolutions') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.regions.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        Torrent Regions
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.distributors.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        Torrent Distributors
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.rss.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-rss"></i>
                        {{ __('staff.rss') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.media_languages.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('common.media-languages') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.cheated_torrents.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-question"></i>
                        Cheated Torrents
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <form method="POST" action="{{ route('staff.flush.peers') }}" x-data>
                        @csrf
                        <button 
                            x-on:click.prevent="Swal.fire({
                                title: 'Are you sure?',
                                text: 'Are you sure you want to delete all ghost peers?',
                                icon: 'warning',
                                showConfirmButton: true,
                                showCancelButton: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $root.submit();
                                }
                            })"
                            class="form__button form__button--text" href
                        >
                            <i class="{{ config('other.font-awesome') }} fa-ghost"></i>
                            {{ __('staff.flush-ghost-peers') }}
                        </button>
                    </form>
                </p>
            </div>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i>
                {{ __('staff.user-tools') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.applications.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-list"></i>
                        {{ __('staff.applications') }} ({{ $apps->pending }})
                        @if ($apps->pending > 0)
                            <x-animation.notification />
                        @endif
                    </a>
                </li>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('user_search') }}">
                        <i class="{{ config('other.font-awesome') }} fa-users"></i>
                        {{ __('staff.user-search') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.watchlist.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-eye"></i>
                        Watchlist
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.gifts.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-gift"></i>
                        {{ __('staff.user-gifting') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.mass-pm.create') }}">
                        <i class="{{ config('other.font-awesome') }} fa-envelope-square"></i>
                        {{ __('staff.mass-pm') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <form method="GET" action="{{ route('staff.mass-actions.validate') }}" x-data>
                        @csrf
                        <button 
                            x-on:click.prevent="Swal.fire({
                                title: 'Are you sure?',
                                text: 'Are you sure you want to automatically validate all users even if their email address isn\'t confirmed?',
                                icon: 'warning',
                                showConfirmButton: true,
                                showCancelButton: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $root.submit();
                                }
                            })"
                            class="form__button form__button--text" href
                        >
                            <i class="{{ config('other.font-awesome') }} fa-history"></i>
                            {{ __('staff.mass-validate-users') }}
                        </button>
                    </form>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.cheaters.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-question"></i>
                        {{ __('staff.possible-leech-cheaters') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.seedboxes.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-server"></i>
                        {{ __('staff.seedboxes') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.internals.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-magic"></i>
                        Internals
                    </a>
                </p>
                @if (auth()->user()->group->is_admin)
                    <p class="form__group form__group--horizontal">
                        <a class="form__button form__button--text" href="{{ route('staff.groups.index') }}">
                            <i class="{{ config('other.font-awesome') }} fa-users"></i>
                            {{ __('staff.groups') }}
                        </a>
                    </p>
                @endif
            </div>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-file"></i>
                {{ __('staff.logs') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.audits.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.audit-log') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.bans.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.bans-log') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.authentications.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.failed-login-log') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.invites.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.invites-log') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.notes.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.user-notes') }}
                    </a>
                </p>
                @if (auth()->user()->group->is_owner)
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.laravellog.index') }}">
                        <i class="fa fa-file"></i> {{ __('staff.laravel-log') }}
                    </a>
                </p>
                @endif
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.reports.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.reports-log') }} ({{ $reports->unsolved }})
                        @if ($reports->unsolved > 0)
                            <x-animation.notification />
                        @endif
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.warnings.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.warnings-log') }}
                    </a>
                </p>
            </div>
        </section>
    </div>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">SSL Certificate</h2>
        <dl class="key-value">
            <dt>URL</dt>
            <dd>{{ config('app.url') }}</dd>
            @if (request()->secure())
                <dt>Connection</dt>
                <dd>Secure</dd>
                <dt>Issued By</dt>
                <dd>{{ (!is_string($certificate)) ? $certificate->getIssuer() : "No Certificate Info Found" }}</dd>
                <dt>Expires</dt>
                <dd>{{ (!is_string($certificate)) ? $certificate->expirationDate()->diffForHumans() : "No Certificate Info Found" }}</dd>
            @else
                <dt>Connection</dt>
                <dd>
                    <strong>Not Secure</strong>
                </dd>
                <dt>Issued By</dt>
                <dd>N/A</dd>
                <dt>Expires</dt>
                <dd>N/A</dd>
            @endif
        </dl>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Server Information</h2>
        <dl class="key-value">
            <dt>OS</dt>
            <dd>{{ $basic['os'] }}</dd>
            <dt>PHP</dt>
            <dd>{{ $basic['php'] }}</dd>
            <dt>Database</dt>
            <dd>{{ $basic['database'] }}</dd>
            <dt>Laravel</dt>
            <dd>{{ $basic['laravel'] }}</dd>
            <dt>{{ config('unit3d.codebase') }}</dt>
            <dd>{{ config('unit3d.version') }}</dd>
        </dl>
    </section>
    <div class="dashboard__stats">
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">Torrents</h2>
            <dl class="key-value">
                <dt>Total</dt>
                <dd>{{ $torrents->total }}</dd>
                <dt>Pending</dt>
                <dd>{{ $torrents->pending }}</dd>
                <dt>Rejected</dt>
                <dd>{{ $torrents->rejected }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">Peers</h2>
            <dl class="key-value">
                <dt>Total</dt>
                <dd>{{ $peers->total }}</dd>
                <dt>Seeds</dt>
                <dd>{{ $peers->seeders }}</dd>
                <dt>Leeches</dt>
                <dd>{{ $peers->leechers }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">Users</h2>
            <dl class="key-value">
                <dt>Total</dt>
                <dd>{{ $users->total }}</dd>
                <dt>Validating</dt>
                <dd>{{ $users->validating }}</dd>
                <dt>Banned</dt>
                <dd>{{ $users->banned }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">RAM</h2>
            <dl class="key-value">
                <dt>Total</dt>
                <dd>{{ $ram['total'] }}</dd>
                <dt>Used</dt>
                <dd>{{ $ram['used'] }}</dd>
                <dt>Free</dt>
                <dd>{{ $ram['available'] }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">Disk</h2>
            <dl class="key-value">
                <dt>Total</dt>
                <dd>{{ $disk['total'] }}</dd>
                <dt>Used</dt>
                <dd>{{ $disk['used'] }}</dd>
                <dt>Free</dt>
                <dd>{{ $disk['free'] }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">Load</h2>
            <dl class="key-value">
                <dt>Average</dt>
                <dd>{{ $avg }}</dd>
            </dl>
        </section>
    </div>
    <section class="panelV2">
        <h2 class="panel__heading">Directory Permissions</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Directory</th>
                        <th>Current</th>
                        <th><abbr title="Recommended">Rec.</abbr></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($file_permissions as $permission)
                        <tr>
                            <td>{{ $permission['directory'] }}</td>
                            <td>
                                @if ($permission['permission'] === $permission['recommended'])
                                    <i class="{{ config('other.font-awesome') }} fa-check-circle"></i>
                                    {{ $permission['permission'] }}
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times-circle"></i>
                                    {{ $permission['permission'] }}
                                @endif
                            </td>
                            <td>{{ $permission['recommended'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
