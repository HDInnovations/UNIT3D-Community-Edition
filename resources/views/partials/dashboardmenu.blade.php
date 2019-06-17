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
            @if (auth()->user()->group->is_owner)
                <li>
                    <a href="{{ route('backupManager') }}">
                        <i class="{{ config('other.font-awesome') }} fa-hdd"></i> @lang('backup.backup') @lang('backup.manager')
                    </a>
                </li>
                <li>
                    <a href="staff_dashboard/config_settings">
                        <i class="{{ config('other.font-awesome') }} fa-copy"></i> @lang('staff.config-manager')
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.commands.index') }}">
                        <i class="fab fa-laravel"></i> Commands
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
            <li>
                <a href="{{ route('Staff.bots.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-robot"></i> @lang('staff.bots')
                </a>
            </li>

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i> @lang('staff.general-tools')
            </li>
            <li>
                <a href="{{ route('staff_article_index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-newspaper"></i> @lang('staff.articles')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.applications.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-list"></i> @lang('staff.applications')
                    <span class="badge badge-danger"> {{ $app_count }} </span>
                </a>
            </li>
            @if (auth()->user()->group->is_admin)
                <li>
                    <a href="{{ route('staff_forum_index') }}">
                        <i class="fab fa-wpforms"></i> @lang('staff.forums')
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff_groups_index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-users"></i> @lang('staff.groups')
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('staff_page_index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.pages')
                </a>
            </li>
            <li>
                <a href="{{ route('getPolls') }}">
                    <i class="{{ config('other.font-awesome') }} fa-chart-pie"></i> @lang('staff.polls')
                </a>
            </li>
            <li>
                <a href="{{ route('Staff.rss.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-rss"></i> @lang('staff.rss')
                </a>
            </li>
            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i> @lang('staff.torrent-tools')
            </li>
            <li>
                <a href="{{ route('staff_torrent_index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file-video"></i> @lang('staff.torrents')
                </a>
            </li>
            <li>
                <a href="{{ route('staff_category_index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.torrent-categories')
                </a>
            </li>
            <li>
                <a href="{{ route('staff_type_index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.torrent-types')
                </a>
            </li>
            <li>
                <a href="{{ route('staff_tag_index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('torrent.genre-tags')
                </a>
            </li>
            <li>
                <a href="{{ route('flush') }}">
                    <i class="fab fa-snapchat-ghost"></i> @lang('staff.flush-ghost-peers')
                </a>
            </li>
            <li>
                <a href="{{ route('moderation') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.torrent-moderation')
                </a>
            </li>

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i> @lang('staff.user-tools')
            </li>
            <li>
                <a href="{{ route('user_search') }}">
                    <i class="{{ config('other.font-awesome') }} fa-users"></i> @lang('staff.user-search')
                </a>
            </li>
            <li>
                <a href="{{ route('systemGift') }}">
                    <i class="{{ config('other.font-awesome') }} fa-gift"></i> @lang('staff.user-gifting')
                </a>
            </li>
            <li>
                <a href="{{ route('massPM') }}">
                    <i class="{{ config('other.font-awesome') }} fa-envelope-square"></i> @lang('staff.mass-pm')
                </a>
            </li>
            <li>
                <a href="{{ route('massValidateUsers') }}">
                    <i class="{{ config('other.font-awesome') }} fa-history"></i> @lang('staff.mass-validate-users')
                </a>
            </li>
            <li>
                <a href="{{ route('leechCheaters') }}">
                    <i class="{{ config('other.font-awesome') }} fa-question"></i> @lang('staff.possible-leech-cheaters')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.seedbox.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-server"></i> @lang('staff.seedboxes')
                </a>
            </li>

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.logs')
            </li>
            <li>
                <a href="{{ route('activity.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.activity-log')
                </a>
            </li>
            <li>
                <a href="{{ route('getBans') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.bans-log')
                </a>
            </li>
            <li>
                <a href="{{ route('getFailedAttemps') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.failed-login-log')
                </a>
            </li>
            <li>
                <a href="{{ route('getInvites') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.invites-log')
                </a>
            </li>
            <li>
                <a href="{{ route('getNotes') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.user-notes')
                </a>
            </li>
            @if (auth()->user()->group->is_owner)
                <li>
                    <a href="/staff/log-viewer">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.laravel-log')
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('getReports') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.reports-log')
                </a>
            </li>
            <li>
                <a href="{{ route('getWarnings') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.warnings-log')
                </a>
            </li>

        </ul>
    </div>
</div>
