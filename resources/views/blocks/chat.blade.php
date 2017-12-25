@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@stop

<div class="col-md-10 col-sm-10 col-md-offset-1">
  <div class="clearfix visible-sm-block"></div>
    <div class="panel panel-chat shoutbox">
      <div class="panel-heading">
        <h4>{{ trans('blocks.chatbox') }}</h4>
      </div>
      <div class="chat-messages">
        <ul class="list-group">
          @foreach($shoutboxItems as $messages)
          @php
          $class = '';
          if (in_array(\Auth::user()->id, explode(',', $messages->mentions))) {
            $class = 'mentioned';
          }
          @endphp
            <li class="list-group-item {{ $class }}">
              @if($messages->poster->image != null)
              <img class="profile-avatar tiny pull-left" src="{{ url('files/img/' . $messages->poster->image) }}">
              @else
              <img class="profile-avatar tiny pull-left" src="{{ url('img/profil.png') }}">
              @endif
              <h4 class="list-group-item-heading"><span class="badge-user text-bold"><i class="{{ $messages->poster->group->icon }}" data-toggle="tooltip" title="" data-original-title="{{ $messages->poster->group->name }}"></i>&nbsp;<a href="{{ route('profil', array('username' => $messages->poster->username, 'id' => $messages->poster->id)) }}" style="color:{{ $messages->poster->group->color }};">{{ $messages->poster->username }}</a>
                @if($messages->poster->isOnline())
                  <i class="fa fa-circle text-green" data-toggle="tooltip" title="" data-original-title="User Is Online!"></i>
                @else
                  <i class="fa fa-circle text-red" data-toggle="tooltip" title="" data-original-title="User Is Offline!"></i>
                @endif
              </span>&nbsp;<span class="text-muted"><small><em>{{ $messages->created_at->diffForHumans() }}</em></small></span>
              </h4>
              <p class="message-content">
                @if(Auth::user()->group->is_modo || $messages->poster->id == Auth::user()->id )
                  <a title="Delete Shout" href="{{route('shout-delete',['id' => $messages->id])}}"><i class="pull-right fa fa-lg fa-times"></i></a>
                @endif

                @emojione(App\Shoutbox::getMessageHtml($messages->message))</p>
            </li>
            @endforeach
        </ul>
      </div>
      <div class="panel-footer ">
        <span class="badge-extra">Type <strong>:</strong> for emoji</span> <span class="badge-extra">BBCode Is Allowed</span> <span class="badge-extra text-red text-bold" style="float:right;">Click [BBCODE] To Enable Editor</span>
          <div class="form-group">
            <textarea class="form-control" id="chat-message"></textarea>
            <p id="chat-error" class="hidden text-danger"></p>
          </div>
      </div>
    </div>
  </div>
<br>

@section('javascripts')
<script type="text/javascript" src="{{ url('js/shout.js') }}"></script>
<script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
<script>
$(document).ready(function() {
  var wbbOpt = {

  }
    $("#chat-message").wysibb(wbbOpt);
});
</script>
@stop
