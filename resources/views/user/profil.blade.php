@extends('layout.default')

@section('title')
<title>{{ $user->username }} - {{ trans('common.members') }} - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
<meta name="description" content="{{ 'Profil de l\'utilisateur ' . $user->username . ' sur le site ' . Config::get('other.title') . '. Découvrer son profil RLM en intégralité en vous inscrivant.' }}"> @stop

@section('breadcrumb')
<li>
  <a href="{{ route('profil', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
  @if( $user->private_profile == 1 && Auth::user()->id != $user->id && !Auth::user()->group->is_modo )
  <div class="container">
    <div class="jumbotron shadowed">
      <div class="container">
        <h1 class="mt-5 text-center">
        <i class="fa fa-times text-danger"></i> Attention: This Profile Has Been Set To PRIVATE!
      </h1>
        <div class="separator"></div>
        <p class="text-center">You are not authorized to view this page. This member prefers to be hidden like a ninja!</p>
      </div>
    </div>
  </div>
  @else
  <div class="well">
    <div class="row">
      <div class="col-md-12 profile-footer">
        {{ $user->username }}'s Recent Achievements:
          @foreach($achievements as $a)
          <img src="/img/badges/{{ $a->details->name }}.png" data-toggle="tooltip" title="" height="50px" data-original-title="{{ $a->details->name }}">
          @endforeach
          <div class="col-xs-1 matches-won"><i class="fa fa-trophy text-success"></i><span>{{ $user->unlockedAchievements()->count() }}</span></div>
      </div>
    </div>
  </div>
  <div class="well">
    <div class="row">
      <div class="col-md-12 profile-footer">
        {{ $user->username }}'s Follower's:
          @foreach($followers as $f)
          @if($f->user->image != null)
          <a href="{{ route('profil', ['username' => $f->user->username, 'id' => $f->user_id]) }}">
          <img src="{{ url('files/img/' . $f->user->image) }}" data-toggle="tooltip" title="{{ $f->user->username }}" height="50px" data-original-title="{{ $f->user->username }}">
          </a>
          @else
          <a href="{{ route('profil', ['username' => $f->user->username, 'id' => $f->user_id]) }}">
          <img src="{{ url('img/profil.png') }}" data-toggle="tooltip" title="{{ $f->user->username }}" height="50px" data-original-title="{{ $f->user->username }}">
          </a>
          @endif
          @endforeach
        <div class="col-xs-1 matches-won"><i class="fa fa-group text-success"></i><span>{{count($followers)}}</span></div>
      </div>
    </div>
  </div>
  <div class="block">
    <div class="header gradient blue">
      <div class="inner_content">
        <div class="content">
          <div class="col-md-2">
            @if($user->image != null)
            <img src="{{ url('files/img/' . $user->image) }}" alt="{{{ $user->username }}}" class="img-circle">
            @else
            <img src="{{ url('img/profil.png') }}" alt="{{{ $user->username }}}" class="img-circle">
            @endif
          </div>
        <div class="col-md-10">
        <h2>{{ $user->username }}
          @if($user->isOnline())
          <i class="fa fa-circle text-green" data-toggle="tooltip" title="" data-original-title="User Is Online!"></i>
          @else
          <i class="fa fa-circle text-red" data-toggle="tooltip" title="" data-original-title="User Is Offline!"></i>
          @endif
          @if($user->getWarning() > 0)<i class="fa fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="Active Warning"></i>@endif
          @if($notes > 0 && Auth::user()->group->is_modo)<i class="fa fa-comment fa-beat" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="Staff Noted Account"></i>@endif
        </h2>
        <h4>Rank: <span class="badge-user text-bold" style="color:{{ $user->group->color }}"><i class="{{ $user->group->icon }}" data-toggle="tooltip" title="" data-original-title="{{ $user->group->name }}"></i> {{ $user->group->name }}</span></h4>
        <h4>Member Since {{ date('M d Y', $user->created_at->getTimestamp()) }}</h4>
        <span style="float:left;">
        @if(Auth::user()->id != $user->id)
        @if(Auth::user()->isFollowing($user->id))
        <a href="{{ route('unfollow', ['user' => $user->id]) }}" id="delete-follow-{{ $user->target_id }}" class="btn btn-xs btn-info" title="Unfollow"><i class="fa fa-user"></i> Unfollow {{ $user->username }}</a>
        @else
        <a href="{{ route('follow', ['user' => $user->id]) }}" id="follow-user-{{ $user->id }}" class="btn btn-xs btn-success" title="Follow"><i class="fa fa-user"></i> Follow {{ $user->username }}</a>
        @endif
        <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal_user_report"><i class="fa fa-eye"></i> Report User</button>
        @endif
        </span>
        <span style="float:right;">
        @if(Auth::check() && Auth::user()->group->is_modo)
        @if($user->group->id == 5)
        <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#modal_user_unban"><span class="fa fa-undo"></span> Unban User </button>
        @else
        <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal_user_ban"><span class="fa fa-ban"></span> Ban User</button>
        @endif
        <a href="{{ route('user_setting', ['username' => $user->username, 'id' => $user->id]) }}" class="btn btn-xs btn-warning"><span class="fa fa-pencil"></span> Edit User </a>
        <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal_user_delete"><span class="fa fa-trash"></span> Delete User </button>
        @endif
        </span>
      </div>
    </div>
  </div>
</div>

    <h3><i class="fa fa-unlock"></i> Public Info</h3>
    <table class="table table-condensed table-bordered table-striped">
      <tbody>
        <tr>
          <td colspan="2">
            <ul class="list-inline mb-0">
              <li>
                <span class="badge-extra text-green text-bold"><i class="fa fa-upload"></i> Total Uploads: {{ $num_uploads }}</span>
              </li>
              <li>
                <span class="badge-extra text-red text-bold"><i class="fa fa-download"></i> Total Downloads: {{ $num_downloads }}</span>
              </li>
              <li>
                <span class="badge-extra text-green text-bold"><i class="fa fa-cloud-upload"></i> Total Seeding: {{ $user->getSeeding() }}</span>
              </li>
              <li>
                <span class="badge-extra text-red text-bold"><i class="fa fa-cloud-download"></i> Total Leeching: {{ $user->getLeeching() }}</span>
              </li>
            </ul>
          </td>
        </tr>
  </div>
  <tr>
    <td>Downloaded</td>
    <td>
      <span class="badge-extra text-red" data-toggle="tooltip" title="" data-original-title="Recorded Download">{{ $user->getDownloaded() }}</span> -
      <span class="badge-extra text-yellow" data-toggle="tooltip" title="" data-original-title="Download Removed From BON Store">N/A</span> =
      <span class="badge-extra text-orange" data-toggle="tooltip" title="" data-original-title="True Download">N/A</span></td>
  </tr>
  <tr>
    <td>Uploaded</td>
    <td>
      <span class="badge-extra text-green" data-toggle="tooltip" title="" data-original-title="Recorded Upload">{{ $user->getUploaded() }}</span> -
      <span class="badge-extra text-yellow" data-toggle="tooltip" title="" data-original-title="Upload Added From BON Store">N/A</span> =
      <span class="badge-extra text-orange" data-toggle="tooltip" title="" data-original-title="True Upload">N/A</span></td>
  </tr>
  <tr>
    <td>Ratio</td>
    <td><span class="badge-user group-member">{{ $user->getRatioString() }}</span></td>
  </tr>
  <tr>
    <td>Total Seedtime (All Torrents)</td>
    <td><span class="badge-user group-member">{{ App\Helpers\StringHelper::timeElapsed($seedtime) }}</span></td>
  </tr>
  <tr>
    <td>Average Seedtime (Per Torrent)</td>
    <td><span class="badge-user group-member">{{ App\Helpers\StringHelper::timeElapsed(round($seedtime / max(1, $hiscount))) }}</span></td>
  </tr>
  <tr>
    <td>Badges</td>
    <td>
    @if($user->getSeeding() >= 150)
    <span class="badge-user" style="background-color:#3fb618; color:white;" data-toggle="tooltip" title="" data-original-title="Seeding 150 Or More Torrents!"><i class="fa fa-upload"></i> Certified Seeder!</span>
    @endif
    @if($num_downloads >= 100)
    <span class="badge-user" style="background-color:#ff0039; color:white;" data-toggle="tooltip" title="" data-original-title="Downloaded 100 Or More Torrents!"><i class="fa fa-download"></i> Certified Downloader!</span>
    @endif
    @if($user->getSeedbonus() >= 50000)
    <span class="badge-user" style="background-color:#9400d3; color:white;" data-toggle="tooltip" title="" data-original-title="Has 50,000 Or More BON In Bank"><i class="fa fa-star"></i> Certified Banker!</span>
    @endif
    </td>
  </tr>
  <tr>
    <td>Title</td>
    <td>
      <span class="badge-extra">{{ $user->title }}</span>
    </td>
  </tr>
  <tr>
    <td>About Me</td>
    <td>
      <span class="badge-extra">@emojione($user->getAboutHtml())</span>
    </td>
  </tr>
  <tr>
    <td>Extra</td>
    <td>
      <ul class="list-inline mb-0">
        <li>
          <span class="badge-extra"><strong>Thanks Received:</strong>
            <span class="text-pink text-bold">--</span>
          </span>
        </li>
        <li>
          <span class="badge-extra"><strong>Thanks Given:</strong>
            <span class="text-green text-bold">{{ $thanks_given }}</span>
          </span>
        </li>
        <li>
          <span class="badge-extra"><strong>Article Comments Made:</strong>
            <span class="text-purple text-bold">{{ $art_comments }}</span>
          </span>
        </li>
        <li>
          <span class="badge-extra"><strong>Torrent Comments Made:</strong>
            <span class="text-purple text-bold">{{ $tor_comments }}</span>
          </span>
        </li>
        <li>
          <span class="badge-extra"><strong>Request Comments Made:</strong>
            <span class="text-purple text-bold">{{ $req_comments }}</span>
          </span>
        </li>
        <li>
          <span class="badge-extra"><strong>Forum Topics Made:</strong>
            <span class="text-purple text-bold">{{ $topics }}</span>
          </span>
        </li>
        <li>
          <span class="badge-extra"><strong>Forum Posts Made:</strong>
            <span class="text-purple text-bold">{{ $posts }}</span>
          </span>
        </li>
        <li>
          <span class="badge-extra"><strong>Referrals:</strong>
            <span class="text-purple text-bold">--</span>
          </span>
        </li>
        <li>
          <span class="badge-extra"><strong>Hit and Run Count (All Time):</strong>
            <span class="text-red text-bold">{{ $user->hitandruns }}</span>
          </span>
        </li>
      </ul>
    </td>
  </tr>
  <tr>
    <td>Warnings</td>
    <td>
      <span class="badge-extra text-red text-bold"><strong>Active Warnings: {{ $warnings->count() }} / {!! Config::get('hitrun.max_warnings') !!}</strong></span>
      @if(Auth::check() && Auth::user()->group->is_modo)
      <a href="{{ route('warninglog', ['username' => $user->username, 'id' => $user->id]) }}"><span class="badge-extra text-bold"><strong>Warning Log</strong></span></a>
      @endif
      <div class="progress">
        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" style="width:.1%;border-bottom-color: #8c0408">
        </div>
        @foreach($warnings as $warning)
        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" style="width:33.3%;border-bottom-color: #8c0408">
          WARNING
        </div>
        @endforeach
      </div>
    </td>
  </tr>
  </tbody>
  </table>
</div>

  @if(Auth::check() && (Auth::user()->id == $user->id || Auth::user()->group->is_modo))
  <div class="block">
  <h3><i class="fa fa-lock"></i> Private Info</h3>
  <table class="table table-condensed table-bordered table-striped">
    <tbody>
      <tr>
        <td class="col-sm-3"> PID</td>
        <td>
          <div class="row">
            <div class="col-sm-2">
              <button type="button" class="btn btn-xxs btn-info collapsed" data-toggle="collapse" data-target="#pid_block" aria-expanded="false">Show PID</button>
            </div>
            <div class="col-sm-10">
              <div id="pid_block" class="collapse" aria-expanded="false" style="height: 0px;">
                <span class="text-monospace">{{ $user->passkey }}</span>
                <br>
              </div>
              <span class="small text-red">PID is like your password, you must keep it safe!</span>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td> User ID</td>
        <td>{{ $user->id }}</td>
      </tr>
      <tr>
        <td> Email</td>
        <td>{{ $user->email }}</td>
      </tr>
      <tr>
        <td> Last Access</td>
        <td>{{ date('M d Y H:m', $user->updated_at->getTimestamp()) }}</td>
      </tr>
      <tr>
        <td> Can Upload</td>
        @if($user->can_upload == 1)
        <td><span class="text-success text-bold"> YES</span></td>
        @else
        <td><span class="text-danger text-bold"> NO</span></td>
        @endif
      </tr>
      <tr>
        <td> Can Download</td>
        @if($user->can_download == 1)
        <td><span class="text-success text-bold"> YES</span></td>
        @else
        <td><span class="text-danger text-bold"> NO</span></td>
        @endif
      </tr>
      <tr>
        <td> Can Comment</td>
        @if($user->can_comment == 1)
        <td><span class="text-success text-bold"> YES</span></td>
        @else
        <td><span class="text-danger text-bold"> NO</span></td>
        @endif
      </tr>
      <tr>
        <td> Can Request</td>
        @if($user->can_request == 1)
        <td><span class="text-success text-bold"> YES</span></td>
        @else
        <td><span class="text-danger text-bold"> NO</span></td>
        @endif
      </tr>
      <tr>
        <td> Can Chat</td>
        @if($user->can_chat == 1)
        <td><span class="text-success text-bold"> YES</span></td>
        @else
        <td><span class="text-danger text-bold"> NO</span></td>
        @endif
      </tr>
      <tr>
        <td> Can Invite</td>
        @if($user->can_invite == 1)
        <td><span class="text-success text-bold"> YES</span></td>
        @else
        <td><span class="text-danger text-bold"> NO</span></td>
        @endif
      </tr>
      <tr>
        <td> Invites</td>
        @if($user->invites > 0)
        <td><span class="text-success text-bold"> {{ $user->invites }}</span><a href="{{ route('inviteTree', ['username' => $user->username, 'id' => $user->id]) }}"><span class="badge-extra text-bold"><strong>Invite Tree</strong></span></a></td>
        @else
        <td><span class="text-danger text-bold"> {{ $user->invites }}</span><a href="{{ route('inviteTree', ['username' => $user->username, 'id' => $user->id]) }}"><span class="badge-extra text-bold"><strong>Invite Tree</strong></span></a></td>
        @endif
      </tr>
    </tbody>
  </table>
  <br>
</div>

@if(Auth::check() && Auth::user()->id == $user->id)
<div class="block">
  <center>
    <a href="{{ route('user_settings', ['username' => $user->username, 'id' => $user->id]) }}">
      <button class="btn btn-primary"><span class="fa fa-cog"></span> Account Settings</button>
    </a>
    <a href="{{ route('user_edit_profil', ['username' => $user->username, 'id' => $user->id]) }}">
      <button class="btn btn-primary"><span class="fa fa-user"></span> Edit Profile </button>
    </a>
    <a href="{{ route('invite') }}">
      <button class="btn btn-primary"><span class="fa fa-plus"></span> Invites </button>
    </a>
    <a href="{{ route('user_clients', ['username' => $user->username, 'id' => $user->id]) }}">
      <button class="btn btn-primary"><span class="fa fa-server"></span> My Seedboxes </button>
    </a>
  </center>
</div>
@endif

@if(Auth::check() && (Auth::user()->id == $user->id || Auth::user()->group->is_modo))
<div class="block">
  <center>
    <a href="{{ route('myuploads', ['username' => $user->username, 'id' => $user->id]) }}">
      <button class="btn btn-success"><span class="fa fa-upload"></span> Uploads Table </button>
    </a>
    <a href="{{ route('myactive', ['username' => $user->username, 'id' => $user->id]) }}">
      <button class="btn btn-success"><span class="fa fa-clock-o"></span> Active Table </button>
    </a>
    <a href="{{ route('myhistory', ['username' => $user->username, 'id' => $user->id]) }}">
      <button class="btn btn-success"><span class="fa fa-history"></span> History Table </button>
    </a>
  </center>
</div>
@endif

<div class="block">
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="#hr" aria-controls="hitrun" role="tab" data-toggle="tab">H&amp;R's</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <!-- HitRun -->
    <div role="tabpanel" class="tab-pane active" id="hr">
      <div class="table-responsive">
        <table class="table table-condensed table-striped table-bordered">
          <div class="head"><strong>Torrent H&amp;R History</strong></div>
          <thead>
            <th>Torrent</th>
            <th>Warned on</th>
            <th>Expires on</th>
            <th>Active</th>
        </thead>
        <tbody>
          @foreach($hitrun as $hr)
          <tr>
            <td>
              <a class="view-torrent" data-id="{{ $hr->torrenttitle->id }}" data-slug="{{ $hr->torrenttitle->slug }}" href="{{ route('torrent', array('slug' => $hr->torrenttitle->slug, 'id' => $hr->torrenttitle->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $hr->torrenttitle->name }}">{{ $hr->torrenttitle->name }}</a>
            </td>
            <td>
              {{ $hr->created_at }}
            </td>
            <td>
              {{ $hr->expires_on }}
            </td>
            <td>
              @if($hr->active == 1)
              <span class='label label-success'>Yes</span>
              @else
              <span class='label label-danger'>Expired</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
        </table>
        {{-- {{ $hitrun->links() }} --}}
      </div>
    </div>
  <!-- /HitRun -->
</div>
</div>
@endif
@endif
</div>

@include('user.user_modals', ['user' => $user]) @stop
