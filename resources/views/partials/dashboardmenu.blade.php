<div class="col-sm-4 col-lg-3">
  <div class="block">
  <ul class="nav nav-list">
    <li class="nav-header head">Links</li>
    <li><a href="{{ route('home') }}">Frontend</a></li>
    <li><a href="{{ route('staff_dashboard') }}">Staff Dashboard</a></li>
    @if(Auth::user()->group->is_admin)
    <li><a href="staff_dashboard/config_settings">Config Manager</a></li>
    @endif
    <li class="nav-header head">General Tools</li>
    <li><a href="{!! route('staff_article_index') !!}">Articles</a></li>
    <li><a href="{!! route('staff_blocks_index') !!}">Blocks</a></li>
    @if(Auth::user()->group->is_admin)
    <li><a href="{!! route('staff_forum_index') !!}">Forums</a></li>
    <li><a href="{!! route('staff_groups_index') !!}">Groups</a></li>
    @endif
    <li><a href="{!! route('staff_page_index') !!}">Pages</a></li>
    <li><a href="{!! route('getPolls') !!}">Polls</a></li>
    <li class="nav-header head">Torrent Tools</li>
    <li><a href="{!! route('staff_torrent_index') !!}">Torrents</a></li>
    <li><a href="{!! route('staff_category_index') !!}">Torrent Categories</a></li>
    <li><a href="{!! route('staff_type_index') !!}">Torrent Types</a></li>
    <li><a href="{{ route('getCatalog') }}">Catalog Groups</a></li>
    <li><a href="{{ route('getCatalogTorrent') }}">Catalog Torrents</a></li>
    <li><a href="{{ route('flush') }}">Flush Ghost Peers</a></li>
    <li><a href="{{ route('moderation') }}">Torrent Moderation</a></li>
    <li class="nav-header head">User Tools</li>
    <li><a href="{{ route('user_search') }}">User Search</a></li>
    <li><a href="{{ route('getNotes') }}">User Notes</a></li>
    <li><a href="{{ route('systemGift') }}">User Gifting</a></li>
    <li><a href="{{ route('massPM') }}">Mass PM</a></li>
    <li class="nav-header head">Logs</li>
    <li><a href="{{ route('activityLog') }}">Activity Log</a></li>
    <li><a href="{{ route('getBans') }}">Bans Log</a></li>
    <li><a href="{{ route('getFailedAttemps') }}">Failed Login Log</a></li>
    <li><a href="{{ route('getInvites') }}">Invites Log</a></li>
    @if(Auth::user()->group->is_admin)
    <li><a href="/staff/log-viewer">Laravel Log</a></li>
    @endif
    <li><a href="{{ route('getReports') }}">Reports Log</a></li>
    <li><a href="{{ route('getWarnings') }}">Warnings Log</a></li>
  </ul>
 </div>
</div>
