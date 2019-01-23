<div class="col-sm-2 col-lg-2">
    <div class="block">
        <ul class="nav nav-list">

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-link"></i> @lang('staff.links')
            </li>
            <li>
                <a href="{{ route('home') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.frontend')
                </a>
            </li>
            <li>
                <a href="{{ route('staff_dashboard') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.staff-dashboard')
                </a>
            </li>
            @if (auth()->user()->group->is_admin)
                <li>
                    <a href="{{ route('staff.backups.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-hdd"></i> @lang('backup.backup') @lang('backup.manager')
                    </a>
                </li>
                <li>
                    <a href="staff_dashboard/config_settings">
                        <i class="{{ config('other.font-awesome') }} fa-copy"></i> @lang('staff.config-manager')
                    </a>
                </li>
            @endif

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i> @lang('staff.chat-tools')
            </li>
            <li>
                <a href="{{ route('chatManager') }}">
                    <i class="{{ config('other.font-awesome') }} fa-comment-dots"></i> @lang('staff.chat')
                </a>
            </li>
            <li>
                <a href="{{ route('flush_chat') }}">
                    <i class="{{ config('other.font-awesome') }} fa-broom"></i> @lang('staff.flush-chat')
                </a>
            </li>

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i> @lang('staff.general-tools')
            </li>
            <li>
                <a href="{{ route('staff.articles.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-newspaper"></i> @lang('staff.articles')
                </a>
            </li>
            @if (auth()->user()->group->is_admin)
                <li>
                    <a href="{{ route('staff.forums.index') }}">
                        <i class="fab fa-wpforms"></i> @lang('staff.forums')
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.groups.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-users"></i> @lang('staff.groups')
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('staff.pages.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.pages')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.polls.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-chart-pie"></i> @lang('staff.polls')
                </a>
            </li>
            <li>
                <a href="{{ route('Staff.rss.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-rss"></i> @lang('rss.rss')
                </a>
            </li>
            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i> @lang('staff.torrent-tools')
            </li>
            <li>
                <a href="{{ route('staff.torrents.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file-video"></i> @lang('staff.torrents')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.categories.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.torrent-categories')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.types.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.torrent-types')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.tags.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> Torrent Tags (Genres)
                </a>
            </li>
            <li>
                <a href="{{ route('getCatalog') }}">
                    <i class="{{ config('other.font-awesome') }} fa-book"></i> @lang('staff.catalog-groups')
                </a>
            </li>
            <li>
                <a href="{{ route('getCatalogTorrent') }}">
                    <i class="{{ config('other.font-awesome') }} fa-book"></i> @lang('staff.catalog-torrents')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.flush.destroy') }}">
                    <i class="fab fa-snapchat-ghost"></i> @lang('staff.flush-ghost-peers')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.moderation.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.torrent-moderation')
                </a>
            </li>

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i> @lang('staff.user-tools')
            </li>
            <li>
                <a href="{{ route('staff.users.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-users"></i> @lang('staff.user-search')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.gifts.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-gift"></i> @lang('staff.user-gifting')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.masspm.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-envelope-square"></i> @lang('staff.mass-pm')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.massvalidate.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-history"></i> @lang('staff.mass-validate-users')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.leechcheaters.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-question"></i> @lang('staff.possible-leech-cheaters')
                </a>
            </li>

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.logs')
            </li>
            <li>
                <a href="{{ route('staff.activity.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.activity-log')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.bans.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.bans-log')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.failedlogins.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.failed-login-log')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.invites.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.invites-log')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.notes.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.user-notes')
                </a>
            </li>
            @if (auth()->user()->group->is_admin)
                <li>
                    <a href="/staff/log-viewer">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.laravel-log')
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('staff.reports.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.reports-log')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.warnings.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.warnings-log')
                </a>
            </li>

        </ul>
    </div>
</div>
