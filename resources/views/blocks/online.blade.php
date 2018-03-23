<div class="col-md-10 col-sm-10 col-md-offset-1">
  <div class="clearfix visible-sm-block"></div>
  <div class="panel panel-chat shoutbox">
    <div class="panel-heading">
      <h4>{{ trans('blocks.users-online') }}<small> ({{ trans('blocks.active-in-last') }} 15 min)</small></h4></div>
    <div class="panel-body">
      @foreach($user as $u)
      @if($u->isOnline())
      @if($u->hidden == 1)
      <span class="badge-user text-orange text-bold" style="margin-bottom: 10px;">{{ strtoupper(trans('common.hidden')) }} @if(auth()->user()->group->is_modo)<a href="{{ route('profile', array('username' => $u->username, 'id' => $u->id)) }}"> ({{ $u->username }} @if($u->getWarning() > 0)<i class="fa fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="{{ trans('common.active-warning') }}"></i>@endif)</a>@endif</span>
      @else
      <a href="{{ route('profile', array('username' => $u->username, 'id' => $u->id)) }}"><span class="badge-user text-bold" style="color:{{ $u->group->color }}; background-image:{{ $u->group->effect }}; margin-bottom: 10px;"><i class="{{ $u->group->icon }}" data-toggle="tooltip" title="" data-original-title="{{ $u->group->name }}"></i> {{ $u->username }} @if($u->getWarning() > 0)<i class="fa fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="{{ trans('common.active-warning') }}"></i>
      @endif
      </span></a>
      @endif
      @endif
      @endforeach
    </div>
    <div class="panel-footer">
      <div class="row">
        <div class="col-sm-12">
          <center>
            <span class="badge-user text-orange text-bold"><i class="fa fa-eye-slash" aria-hidden="true"></i>{{ strtoupper(trans('common.hidden')) }}</span>
            @foreach($groups as $group)
            <span class="badge-user text-bold" style="color:{{ $group->color }}; background-image:{{ $group->effect }};"><i class="{{ $group->icon }}" aria-hidden="true"></i> {{ $group->name }}</span>
            @endforeach
          </center>
        </div>
      </div>
    </div>
  </div>
</div>
