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
          @foreach($shoutboxMessages as $message)
          @php
          $class = '';
          if (in_array(\Auth::user()->id, explode(',', $message->mentions))) {
            $class = 'mentioned';
          }

          $messageHtml = App\Shoutbox::getMessageHtml($message->message);
          $messageHtml = \LaravelEmojiOne::toImage($messageHtml);
          $messageHtml = App\Helpers\LanguageCensor::censor($messageHtml);

          @endphp
            <li class="list-group-item {{ $class }}">
              @if($message->poster->image != null)
              <img class="profile-avatar tiny pull-left" src="{{ url('files/img/' . $message->poster->image) }}">
              @else
              <img class="profile-avatar tiny pull-left" src="{{ url('img/profil.png') }}">
              @endif
              <h4 class="list-group-item-heading"><span class="badge-user text-bold"><i class="{{ $message->poster->group->icon }}" data-toggle="tooltip" title="" data-original-title="{{ $message->poster->group->name }}"></i>&nbsp;<a href="{{ route('profil', array('username' => $message->poster->username, 'id' => $message->poster->id)) }}" style="color:{{ $message->poster->group->color }};">{{ $message->poster->username }}</a>
                @if($message->poster->isOnline())
                  <i class="fa fa-circle text-green" data-toggle="tooltip" title="" data-original-title="User Is Online!"></i>
                @else
                  <i class="fa fa-circle text-red" data-toggle="tooltip" title="" data-original-title="User Is Offline!"></i>
                @endif
              </span>&nbsp;<span class="text-muted"><small><em>{{ $message->created_at->diffForHumans() }}</em></small></span>
              </h4>
              <p class="message-content">
                @if(Auth::user()->group->is_modo || $message->poster->id == Auth::user()->id )
                  <a title="Delete Shout" href="{{route('shout-delete',['id' => $message->id])}}"><i class="pull-right fa fa-lg fa-times"></i></a>
                @endif

                {!! $messageHtml !!}
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
<script type="text/javascript" src="{{ url('js/shout.js?v=03') }}"></script>
<script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
<script>
$(document).ready(function() {
  var wbbOpt = {

  }
    $("#chat-message").wysibb(wbbOpt);
});
</script>
@stop
