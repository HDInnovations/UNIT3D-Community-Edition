<div class="col-sm-4 col-lg-3">
    <div class="block">
        <ul class="nav nav-list">
            <li class="nav-header head"><i class="{{ config('other.font-awesome') }} fa-link"></i> {{ trans('staff.links') }}</li>
            <li><a href="{{ route('home') }}"><i class="{{ config('other.font-awesome') }} fa-columns"></i> {{ trans('staff.frontend') }}</a></li>
            <li><a href="{{ route('staff_dashboard') }}"><i
                            class="{{ config('other.font-awesome') }} fa-columns"></i> {{ trans('staff.staff-dashboard') }}</a></li>
            @if(auth()->user()->group->is_admin)
                <li><a href="{{ route('backupManager') }}"><i
                                class="{{ config('other.font-awesome') }} fa-hdd"></i> {{ trans('backup.backup') }} {{ trans('backup.manager') }}</a>
                </li>
                <li><a href="staff_dashboard/config_settings"><i
                                class="{{ config('other.font-awesome') }} fa-copy"></i> {{ trans('staff.config-manager') }}</a></li>
            @endif

            <li class="nav-header head"><i class="{{ config('other.font-awesome') }} fa-wrench"></i> {{ trans('staff.general-tools') }}</li>
            <li><a href="{{ route('staff_article_index') }}"><i
                            class="{{ config('other.font-awesome') }} fa-newspaper"></i> {{ trans('staff.articles') }}</a></li>
            @if(auth()->user()->group->is_admin)
                <li><a href="{{ route('staff_forum_index') }}"><i class="fab fa-wpforms"></i> {{ trans('staff.forums') }}
                    </a></li>
                <li><a href="{{ route('staff_groups_index') }}"><i class="{{ config('other.font-awesome') }} fa-users"></i> {{ trans('staff.groups') }}
                    </a></li>
            @endif
            <li><a href="{{ route('staff_page_index') }}"><i class="{{ config('other.font-awesome') }} fa-file"></i> {{ trans('staff.pages') }}</a></li>
            <li><a href="{{ route('getPolls') }}"><i class="{{ config('other.font-awesome') }} fa-chart-pie"></i> {{ trans('staff.polls') }}</a></li>
            <li><a href="{{ route('chatManager') }}"><i class="{{ config('other.font-awesome') }} fa-comment-dots"></i> {{ trans('staff.chat') }}</a></li>

            <li class="nav-header head"><i class="{{ config('other.font-awesome') }} fa-wrench"></i> {{ trans('staff.torrent-tools') }}</li>
            <li><a href="{{ route('staff_torrent_index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file-video"></i> {{ trans('staff.torrents') }}</a></li>
            <li><a href="{{ route('staff_category_index') }}"><i
                            class="{{ config('other.font-awesome') }} fa-columns"></i> {{ trans('staff.torrent-categories') }}</a></li>
            <li><a href="{{ route('staff_type_index') }}"><i
                            class="{{ config('other.font-awesome') }} fa-columns"></i> {{ trans('staff.torrent-types') }}</a></li>
            <li><a href="{{ route('getCatalog') }}"><i class="{{ config('other.font-awesome') }} fa-book"></i> {{ trans('staff.catalog-groups') }}</a>
            </li>
            <li><a href="{{ route('getCatalogTorrent') }}"><i
                            class="{{ config('other.font-awesome') }} fa-book"></i> {{ trans('staff.catalog-torrents') }}</a></li>
            <li><a href="{{ route('flush') }}"><i
                            class="fab fa-snapchat-ghost"></i> {{ trans('staff.flush-ghost-peers') }}</a></li>
            <li><a href="{{ route('moderation') }}"><i
                            class="{{ config('other.font-awesome') }} fa-columns"></i> {{ trans('staff.torrent-moderation') }}</a></li>

            <li class="nav-header head"><i class="{{ config('other.font-awesome') }} fa-wrench"></i> {{ trans('staff.user-tools') }}</li>
            <li><a href="{{ route('user_search') }}"><i class="{{ config('other.font-awesome') }} fa-users"></i> {{ trans('staff.user-search') }}</a>
            </li>
            <li><a href="{{ route('systemGift') }}"><i class="{{ config('other.font-awesome') }} fa-gift"></i> {{ trans('staff.user-gifting') }}</a>
            </li>
            <li><a href="{{ route('massPM') }}"><i class="{{ config('other.font-awesome') }} fa-envelope-square"></i> {{ trans('staff.mass-pm') }}</a></li>
            <li><a href="{{ route('massValidateUsers') }}"><i
                            class="{{ config('other.font-awesome') }} fa-history"></i> {{ trans('staff.mass-validate-users') }}</a></li>

            <li class="nav-header head"><i class="{{ config('other.font-awesome') }} fa-file"></i> {{ trans('staff.logs') }}</li>
            <li><a href="{{ route('getActivity') }}"><i class="{{ config('other.font-awesome') }} fa-file"></i> {{ trans('staff.activity-log') }}</a>
            </li>
            <li><a href="{{ route('getBans') }}"><i class="{{ config('other.font-awesome') }} fa-file"></i> {{ trans('staff.bans-log') }}</a></li>
            <li><a href="{{ route('getFailedAttemps') }}"><i
                            class="{{ config('other.font-awesome') }} fa-file"></i> {{ trans('staff.failed-login-log') }}</a></li>
            <li><a href="{{ route('getInvites') }}"><i class="{{ config('other.font-awesome') }} fa-file"></i> {{ trans('staff.invites-log') }}</a></li>
            <li><a href="{{ route('getNotes') }}"><i class="{{ config('other.font-awesome') }} fa-file"></i> {{ trans('staff.user-notes') }}</a></li>
            @if(auth()->user()->group->is_admin)
                <li><a href="/staff/log-viewer"><i class="{{ config('other.font-awesome') }} fa-file"></i> {{ trans('staff.laravel-log') }}</a></li>
            @endif
            <li><a href="{{ route('getReports') }}"><i class="{{ config('other.font-awesome') }} fa-file"></i> {{ trans('staff.reports-log') }}</a></li>
            <li><a href="{{ route('getWarnings') }}"><i class="{{ config('other.font-awesome') }} fa-file"></i> {{ trans('staff.warnings-log') }}</a>
            </li>
        </ul>
    </div>
</div>
