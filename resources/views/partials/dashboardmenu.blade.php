<div class="col-sm-4 col-lg-3">
  <div class="block">
  <ul class="nav nav-list">
    <li class="nav-header head"><i class="fa fa-link"></i> {{ trans('staff.links') }}</li>
    <li><a href="{{ route('home') }}"><i class="fa fa-columns"></i> {{ trans('staff.frontend') }}</a></li>
    <li><a href="{{ route('staff_dashboard') }}"><i class="fa fa-columns"></i> {{ trans('staff.staff-dashboard') }}</a></li>
    @if(auth()->user()->group->is_admin)
    <li><a href="{{ route('backupManager') }}"><i class="fa fa-hdd-o"></i> {{ trans('backup.backup') }} {{ trans('backup.manager') }}</a></li>
    <li><a href="staff_dashboard/config_settings"> {{ trans('staff.config-manager') }}</a></li>
    @endif
    <li class="nav-header head"><i class="fa fa-wrench"></i> {{ trans('staff.general-tools') }}</li>
    <li><a href="{{ route('staff_article_index') }}"><i class="fa fa-newspaper-o"></i> {{ trans('staff.articles') }}</a></li>
    @if(auth()->user()->group->is_admin)
    <li><a href="{{ route('staff_forum_index') }}"><i class="fa fa-wpforms"></i> {{ trans('staff.forums') }}</a></li>
    <li><a href="{{ route('staff_groups_index') }}"><i class="fa fa-users"></i> {{ trans('staff.groups') }}</a></li>
    @endif
    <li><a href="{{ route('staff_page_index') }}"><i class="fa fa-file"></i> {{ trans('staff.pages') }}</a></li>
    <li><a href="{{ route('getPolls') }}"><i class="fa fa-pie-chart"></i> {{ trans('staff.polls') }}</a></li>
    <li class="nav-header head"><i class="fa fa-wrench"></i> {{ trans('staff.torrent-tools') }}</li>
    <li><a href="{{ route('staff_torrent_index') }}"><i class="fa fa-file-movie-o"></i> {{ trans('staff.torrents') }}</a></li>
    <li><a href="{{ route('staff_category_index') }}"><i class="fa fa-columns"></i> {{ trans('staff.torrent-categories') }}</a></li>
    <li><a href="{{ route('staff_type_index') }}"><i class="fa fa-columns"></i> {{ trans('staff.torrent-types') }}</a></li>
    <li><a href="{{ route('getCatalog') }}"><i class="fa fa-book"></i> {{ trans('staff.catalog-groups') }}</a></li>
    <li><a href="{{ route('getCatalogTorrent') }}"><i class="fa fa-book"></i> {{ trans('staff.catalog-torrents') }}</a></li>
    <li><a href="{{ route('flush') }}"><i class="fa fa-snapchat-ghost"></i> {{ trans('staff.flush-ghost-peers') }}</a></li>
    <li><a href="{{ route('moderation') }}"><i class="fa fa-columns"></i> {{ trans('staff.torrent-moderation') }}</a></li>
    <li class="nav-header head"><i class="fa fa-wrench"></i> {{ trans('staff.user-tools') }}</li>
    <li><a href="{{ route('user_search') }}"><i class="fa fa-users"></i> {{ trans('staff.user-search') }}</a></li>
    <li><a href="{{ route('getNotes') }}"><i class="fa fa-comment"></i> {{ trans('staff.user-notes') }}</a></li>
    <li><a href="{{ route('systemGift') }}"><i class="fa fa-gift"></i> {{ trans('staff.user-gifting') }}</a></li>
    <li><a href="{{ route('massPM') }}"><i class="fa fa-mail-forward"></i> {{ trans('staff.mass-pm') }}</a></li>
    <li class="nav-header head"><i class="fa fa-file"></i> {{ trans('staff.logs') }}</li>
    <li><a href="{{ route('activityLog') }}"><i class="fa fa-file"></i> {{ trans('staff.activity-log') }}</a></li>
    <li><a href="{{ route('getBans') }}"><i class="fa fa-file"></i> {{ trans('staff.bans-log') }}</a></li>
    <li><a href="{{ route('getFailedAttemps') }}"><i class="fa fa-file"></i> {{ trans('staff.failed-login-log') }}</a></li>
    <li><a href="{{ route('getInvites') }}"><i class="fa fa-file"></i> {{ trans('staff.invites-log') }}</a></li>
    @if(auth()->user()->group->is_admin)
    <li><a href="/staff/log-viewer"><i class="fa fa-file"></i> {{ trans('staff.laravel-log') }}</a></li>
    @endif
    <li><a href="{{ route('getReports') }}"><i class="fa fa-file"></i> {{ trans('staff.reports-log') }}</a></li>
    <li><a href="{{ route('getWarnings') }}"><i class="fa fa-file"></i> {{ trans('staff.warnings-log') }}</a></li>
  </ul>
 </div>
</div>
