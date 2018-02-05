<div class="col-sm-4 col-lg-3">
  <div class="block">
  <ul class="nav nav-list">
    <li class="nav-header head">{{ trans('staff.links') }}</li>
    <li><a href="{{ route('home') }}">{{ trans('staff.frontend') }}</a></li>
    <li><a href="{{ route('staff_dashboard') }}">{{ trans('staff.staff-dashboard') }}</a></li>
    @if(Auth::user()->group->is_admin)
    <li><a href="staff_dashboard/config_settings">{{ trans('staff.config-manager') }}</a></li>
    @endif
    <li class="nav-header head">{{ trans('staff.general-tools') }}</li>
    <li><a href="{!! route('staff_article_index') !!}">{{ trans('staff.articles') }}</a></li>
    <li><a href="{!! route('staff_blocks_index') !!}">{{ trans('staff.blocks') }}</a></li>
    @if(Auth::user()->group->is_admin)
    <li><a href="{!! route('staff_forum_index') !!}">{{ trans('staff.forums') }}</a></li>
    <li><a href="{!! route('staff_groups_index') !!}">{{ trans('staff.groups') }}</a></li>
    @endif
    <li><a href="{!! route('staff_page_index') !!}">{{ trans('staff.pages') }}</a></li>
    <li><a href="{!! route('getPolls') !!}">{{ trans('staff.pools') }}</a></li>
    <li class="nav-header head">{{ trans('staff.torrent-tools') }}</li>
    <li><a href="{!! route('staff_torrent_index') !!}">{{ trans('staff.torrents') }}</a></li>
    <li><a href="{!! route('staff_category_index') !!}">{{ trans('staff.torrent-categories') }}</a></li>
    <li><a href="{!! route('staff_type_index') !!}">{{ trans('staff.torrent-types') }}</a></li>
    <li><a href="{{ route('getCatalog') }}">{{ trans('staff.catalog-groups') }}</a></li>
    <li><a href="{{ route('getCatalogTorrent') }}">{{ trans('staff.catalog-torrents') }}</a></li>
    <li><a href="{{ route('flush') }}">{{ trans('staff.flush-ghost-peers') }}</a></li>
    <li><a href="{{ route('moderation') }}">{{ trans('staff.torrent-moderation') }}</a></li>
    <li class="nav-header head">{{ trans('staff.user-tools') }}</li>
    <li><a href="{{ route('user_search') }}">{{ trans('staff.user-search') }}</a></li>
    <li><a href="{{ route('getNotes') }}">{{ trans('staff.user-notes') }}</a></li>
    <li><a href="{{ route('systemGift') }}">{{ trans('staff.user-gifting') }}</a></li>
    <li><a href="{{ route('massPM') }}">{{ trans('staff.mass-pm') }}</a></li>
    <li class="nav-header head">{{ trans('staff.logs') }}</li>
    <li><a href="{{ route('activityLog') }}">{{ trans('staff.activity-log') }}</a></li>
    <li><a href="{{ route('getBans') }}">{{ trans('staff.bans-log') }}</a></li>
    <li><a href="{{ route('getFailedAttemps') }}">{{ trans('staff.failed-login-log') }}</a></li>
    <li><a href="{{ route('getInvites') }}">{{ trans('staff.invites-log') }}</a></li>
    @if(Auth::user()->group->is_admin)
    <li><a href="/staff/log-viewer">{{ trans('staff.laravel-log') }}</a></li>
    @endif
    <li><a href="{{ route('getReports') }}">{{ trans('staff.reports-log') }}</a></li>
    <li><a href="{{ route('getWarnings') }}">{{ trans('staff.warnings-log') }}</a></li>
  </ul>
 </div>
</div>
